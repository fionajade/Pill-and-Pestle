<?php

/* ---------------- INITIALIZE SESSION ---------------- */
if (!isset($_SESSION['chat_initialized'])) {
    $_SESSION['chat_messages'] = [];
    $_SESSION['chat_initialized'] = true;
}

/* ---------------- DATABASE CONFIG ---------------- */
$conn = new mysqli("127.0.0.1", "root", "", "meditrack");
if ($conn->connect_error) {
    die(json_encode(['ok' => false, 'error' => 'Database connection failed']));
}

/* ---------------- CONFIG ---------------- */
$NO_DATA_RESPONSE = "Sorry, I cannot answer that question based on the available medicine information.";

/* ---------------- MEDICINE KEYWORDS ---------------- */
$medicineKeywords = ['ibuprofen', 'acetaminophen (paracetamol)', 'aspirin', 'naproxen', 'diclofenac', 'tramadol', 
'morphine', 'codeine', 'ketorolac', 'penicillin', 'amoxicillin', 'ciprofloxacin', 'doxycycline', 'azithromycin', 
'cephalexin', 'clindamycin', 'erythromycin', 'metronidazole', 'fluoxetine', 'sertraline', 'citalopram', 'paroxetine', 
'venlafaxine', 'duloxetine', 'bupropion', 'amitriptyline', 'escitalopram', 'insulin', 'metformin', 'glipizide', 'glyburide', 
'pioglitazone', 'sitagliptin', 'empagliflozin', 'liraglutide', 'acarbose', 'phenytoin', 'lamotrigine', 'valproic acid', 
'carbamazepine', 'levetiracetam', 'topiramate', 'gabapentin', 'clonazepam', 'phenobarbital', 'haloperidol', 'risperidone', 
'olanzapine', 'quetiapine', 'aripiprazole', 'clozapine', 'chlorpromazine', 'ziprasidone', 'lurasidone', 'acyclovir', 
'oseltamivir (tamiflu)', 'valacyclovir', 'remdesivir', 'zidovudine (azt)', 'sofosbuvir', 'lamivudine', 'abacavir', 
'tenofovir', 'warfarin', 'heparin', 'enoxaparin', 'dabigatran', 'apixaban', 'rivaroxaban', 'fondaparinux', 'edoxaban', 
'aspirin', 'diphenhydramine', 'loratadine', 'cetirizine', 'fexofenadine', 'chlorpheniramine', 'hydroxyzine', 'levocetirizine', 
'desloratadine', 'promethazine'];

/* ---------------- CATEGORY MAPPING ---------------- */
$queryMapping = [
    'pain' => ['pain', 'NSAID', 'Analgesic', 'opioid'],
    'fever' => ['fever', 'antipyretic'],
    'headache' => ['headache', 'pain', 'NSAID'],
    'infection' => ['antibiotic', 'infection', 'bacterial'],
    'allergy' => ['antihistamine', 'allergy'],
    'diabetes' => ['diabetes', 'insulin', 'antidiabetic'],
    'seizure' => ['seizure', 'anticonvulsant'],
    'depression' => ['depression', 'antidepressant'],
    'antiviral' => ['antiviral', 'virus'],
    'blood thinner' => ['anticoagulant'],
    'schizophrenia' => ['antipsychotic']
];

/* ---------------- HANDLE CHAT ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $userMsg = trim($_POST['message'] ?? '');
    if ($userMsg === '') {
        echo json_encode(['ok' => false, 'error' => 'Empty message']);
        exit;
    }

    $searchKeywords = [];
    $isMedicineRelated = false;

    /* DIRECT MEDICINE MATCH */
    foreach ($medicineKeywords as $med) {
        if (stripos($userMsg, $med) !== false) {
            $searchKeywords[] = $med;
            $isMedicineRelated = true;
        }
    }

    /* CATEGORY MATCH */
    if (empty($searchKeywords)) {
        foreach ($queryMapping as $key => $keywords) {
            if (stripos($userMsg, $key) !== false) {
                $searchKeywords = $keywords;
                $isMedicineRelated = true;
                break;
            }
        }
    }

    /* NOT MEDICINE RELATED */
    if (!$isMedicineRelated) {
        echo json_encode(['ok' => true, 'reply' => $NO_DATA_RESPONSE]);
        exit;
    }

    /* ---------------- DATABASE SEARCH ---------------- */
    $filteredData = '';

    $where = [];
    $params = [];
    $types = '';

    foreach ($searchKeywords as $kw) {
        $where[] = "(name LIKE ? OR description LIKE ?)";
        $params[] = "%$kw%";
        $params[] = "%$kw%";
        $types .= "ss";
    }

    $sql = "SELECT name, brand, description FROM medicines WHERE " . implode(" OR ", $where);
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $filteredData .= "- {$row['name']} ({$row['brand']}): {$row['description']}\n";
        }
        $stmt->close();
    }

    /*  MEDICINE RELATED BUT NO DB RESULT */
    if ($filteredData === '') {
        echo json_encode(['ok' => true, 'reply' => $NO_DATA_RESPONSE]);
        exit;
    }

    /* DB RESULT — RETURN DIRECTLY */
    echo json_encode([
        'ok' => true,
        'reply' => trim($filteredData)
    ]);
    exit;
}

$conn->close();
?>

<!-- ================= CHATBOT UI ================= -->

<style>
    :root {
        --chat-primary: #002147;
        --chat-bg: #ffffff;
        --chat-width: 380px;
    }

    .chatbot-toggler {
        position: fixed;
        bottom: 30px;
        right: 30px;
        height: 60px;
        width: 60px;
        border-radius: 50%;
        background: var(--chat-primary);
        color: #fff;
        border: none;
        cursor: pointer;
        z-index: 9998;
    }

    .chatbot-sidebar {
        position: fixed;
        top: 0;
        right: -450px;
        width: var(--chat-width);
        height: 100%;
        background: var(--chat-bg);
        box-shadow: -5px 0 20px rgba(0, 0, 0, .15);
        display: flex;
        flex-direction: column;
        transition: right .4s ease;
        z-index: 9999;
    }

    .show-chatbot .chatbot-sidebar {
        right: 0;
    }


    .chat-header {
        background: var(--chat-primary);
        color: #fff;
        padding: 15px;
        display: flex;
        justify-content: space-between;
    }

    .chat-box {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background: #f9f9f9;
    }

    .chat-message {
        max-width: 80%;
    }

    .chat-message.user {
        margin-left: auto;
        text-align: right;
    }

    .message-content {
        padding: 10px 14px;
        border-radius: 14px;
        margin-bottom: 10px;
        font-size: .9rem;
    }

    .user .message-content {
        background: var(--chat-primary);
        color: #fff;
    }

    .bot .message-content {
        background: #e9ecef;
    }


    .chat-input {
        display: flex;
        gap: 10px;
        padding: 10px;
        border-top: 1px solid #ddd;
    }

    .chat-input textarea {
        flex: 1;
        resize: none;
        height: 42px;
        border-radius: 20px;
        padding: 10px 14px;
    }

    .send-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: none;
        background: var(--chat-primary);
        color: #fff;
    }
</style>


<!-- Floating Trigger Button -->
<button class="chatbot-toggler" onclick="toggleChat()">
    <!-- Message Bubble Icon -->
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</button>


<!-- The Sidebar -->
<div class="chatbot-sidebar">
    <div class="chat-header">
        <div style="display:flex; align-items:center; gap:10px;">
            <!-- Logo -->
            <div
                style="width:30px; height:30px; background:white; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                <span style="color:#002147; font-weight:bold;">M</span>
            </div>
            <h2>MediTrack AI</h2>
        </div>
        <button class="close-btn" onclick="toggleChat()">&times;</button>
    </div>


    <div class="chat-box">
        <!-- Default Welcome Message -->
        <div class="chat-message bot">
            <div class="message-content">
                Hello! I'm your MediTrack Assistant. How can I help you find medicines today?
            </div>
        </div>
    </div>


    <div class="chat-input">
        <textarea placeholder="Type a message..." required></textarea>
        <button class="send-btn">
            <!-- Send Icon -->
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </button>
    </div>
</div>

<script>
    const body = document.body;
    const sendBtn = document.querySelector(".send-btn");
    const chatInput = document.querySelector(".chat-input textarea");
    const chatBox = document.querySelector(".chat-box");

    function toggleChat() {
        body.classList.toggle("show-chatbot");
    }

    function appendMessage(role, text) {
        // Split by newline or dash to format as separate lines
        const lines = text.split(/\n| - /).filter(line => line.trim() !== '');

        let formattedText = lines.map(line => {
            // Highlight medicine name before colon if exists
            if (line.includes(':')) {
                const [name, desc] = line.split(/:(.+)/); // split only at first colon
                return `<strong>${name.trim()}:</strong> ${desc.trim()}`;
            }
            return line.trim();
        }).join('<br>'); // use <br> for line breaks

        chatBox.innerHTML += `
            <div class="chat-message ${role}">
                <div class="message-content">${formattedText}</div>
            </div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    async function handleChat() {
        const msg = chatInput.value.trim();
        if (!msg) return;


        appendMessage("user", msg);
        chatInput.value = "";


        appendMessage("bot", "Typing...");


        try {
            const res = await fetch("chatbot.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ action: "send", message: msg })
            });


            const data = await res.json();
            chatBox.lastElementChild.remove();


            appendMessage("bot", data.ok ? data.reply : "Error occurred.");
        } catch {
            appendMessage("bot", "Unable to connect.");
        }
    }


    sendBtn.onclick = handleChat;
    chatInput.onkeydown = e => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleChat();
        }
    };

</script>