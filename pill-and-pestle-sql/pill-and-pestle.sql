-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 10:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pill-and-pestle`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Analgesics'),
(2, 'Antibiotics'),
(3, 'Antidepressants'),
(4, 'Antidiabetics'),
(5, 'Antiepileptics'),
(6, 'Antipsychotics'),
(7, 'Antivirals'),
(8, 'Anticoagulants'),
(9, 'Antihistamines');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `name`, `brand`, `description`, `quantity`, `unit_price`, `expiry_date`, `added_on`, `category_id`, `supplier_id`, `img`) VALUES
(1, 'Ibuprofen', 'Advil', 'Nonsteroidal anti-inflammatory drug (NSAID)', 105, 89.85, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Ibruprofen.png'),
(2, 'Acetaminophen (Paracetamol)', 'Tylenol', 'Pain reliever and fever reducer', 201, 67.50, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Acetaminophen.png'),
(3, 'Aspirin', 'Bayer', 'Used for pain, fever, and inflammation', 85, 59.85, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Aspirin.png'),
(4, 'Naproxen', 'Aleve', 'NSAID for inflammation and pain', 78, 93.75, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Naproxen.png'),
(5, 'Diclofenac', 'Voltaren', 'NSAID used to treat pain and inflammation', 55, 105.00, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Diclofenac.png'),
(6, 'Tramadol', 'Ultram', 'Opioid pain medication', 34, 194.85, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Tramadol.png'),
(7, 'Morphine', 'MS Contin', 'Strong opioid painkiller', 50, 225.00, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Morphine.png'),
(8, 'Codeine', 'Tylenol #3', 'Mild to moderate pain relief', 80, 131.25, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Codeine.png'),
(9, 'Ketorolac', 'Toradol', 'Short-term pain management', 68, 138.00, '2026-12-31', '2025-06-10 18:04:38', 1, NULL, 'Ketorolac.png'),
(10, 'Penicillin', 'Pfizerpen', 'Antibiotic used for bacterial infections', 120, 72.00, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Penicillin.png'),
(11, 'Amoxicillin', 'Amoxil', 'Broad-spectrum penicillin antibiotic', 100, 82.50, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Amoxicillin.png'),
(12, 'Ciprofloxacin', 'Cipro', 'Fluoroquinolone antibiotic', 80, 93.00, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Ciprofloxacin.png'),
(13, 'Doxycycline', 'Vibramycin', 'Tetracycline antibiotic', 85, 101.25, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Doxycycline.png'),
(14, 'Azithromycin', 'Zithromax', 'Macrolide antibiotic', 95, 105.00, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Azithromycin.png'),
(15, 'Cephalexin', 'Keflex', 'Cephalosporin antibiotic', 100, 88.50, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Cephalexin.png'),
(16, 'Clindamycin', 'Cleocin', 'Lincosamide antibiotic', 90, 102.00, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Clindamycin.png'),
(17, 'Erythromycin', 'Ery-Tab', 'Macrolide antibiotic', 80, 82.50, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Erythromycin.png'),
(18, 'Metronidazole', 'Flagyl', 'Antibiotic and antiprotozoal', 75, 71.25, '2026-12-31', '2025-06-10 18:04:38', 2, NULL, 'Metronidazole.png'),
(19, 'Fluoxetine', 'Prozac', 'SSRI used to treat depression', 100, 127.50, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Fluoxetine.png'),
(20, 'Sertraline', 'Zoloft', 'SSRI for depression and anxiety', 95, 123.75, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Sertraline.png'),
(21, 'Citalopram', 'Celexa', 'SSRI for mood disorders', 90, 118.50, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Citalopram.png'),
(22, 'Paroxetine', 'Paxil', 'SSRI for depression and OCD', 85, 114.00, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Paroxetine.png'),
(23, 'Venlafaxine', 'Effexor', 'SNRI for depression', 80, 135.00, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Venlafaxine.png'),
(24, 'Duloxetine', 'Cymbalta', 'SNRI for depression and anxiety', 75, 142.50, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Duloxetine.png'),
(25, 'Bupropion', 'Wellbutrin', 'Atypical antidepressant', 70, 150.00, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Bupropion.png'),
(26, 'Amitriptyline', 'Elavil', 'Tricyclic antidepressant', 65, 100.50, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Amitriptyline.png'),
(27, 'Escitalopram', 'Lexapro', 'SSRI for depression and anxiety', 90, 124.50, '2026-12-31', '2025-06-10 18:04:38', 3, NULL, 'Escitalopram.png'),
(28, 'Insulin', 'Humulin', 'Hormone for blood sugar control', 200, 375.00, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Insulin.png'),
(29, 'Metformin', 'Glucophage', 'First-line treatment for type 2 diabetes', 180, 108.75, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Metformin.png'),
(30, 'Glipizide', 'Glucotrol', 'Sulfonylurea to lower blood glucose', 150, 90.00, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Glipizide.png'),
(31, 'Glyburide', 'Diabeta', 'Oral diabetes medication', 140, 91.50, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Glyburide.png'),
(32, 'Pioglitazone', 'Actos', 'Increases insulin sensitivity', 130, 117.00, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Pioglitazone.png'),
(33, 'Sitagliptin', 'Januvia', 'DPP-4 inhibitor for diabetes', 125, 138.00, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Sitagliptin.png'),
(34, 'Empagliflozin', 'Jardiance', 'SGLT2 inhibitor for blood sugar control', 120, 157.50, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Empagliflozin.png'),
(35, 'Liraglutide', 'Victoza', 'GLP-1 receptor agonist', 110, 225.00, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Liraglutide.png'),
(36, 'Acarbose', 'Precose', 'Slows carbohydrate digestion', 100, 89.25, '2026-12-31', '2025-06-10 18:04:38', 4, NULL, 'Acarbose.png'),
(37, 'Phenytoin', 'Dilantin', 'Controls seizures', 90, 102.00, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Phenytoin.png'),
(38, 'Lamotrigine', 'Lamictal', 'Used for epilepsy and bipolar disorder', 85, 118.50, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Lamotrigine.png'),
(39, 'Valproic acid', 'Depakote', 'Seizure and mood stabilizer', 80, 127.50, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Valproic_acid.png'),
(40, 'Carbamazepine', 'Tegretol', 'Anticonvulsant and mood stabilizer', 75, 99.00, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Carbamazepine.png'),
(41, 'Levetiracetam', 'Keppra', 'Anticonvulsant', 70, 135.00, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Levetiracetam.png'),
(42, 'Topiramate', 'Topamax', 'Used for epilepsy and migraines', 65, 124.50, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Topiramate.png'),
(43, 'Gabapentin', 'Neurontin', 'Treats nerve pain and seizures', 60, 108.75, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Gabapentin.png'),
(44, 'Clonazepam', 'Klonopin', 'Benzodiazepine used for seizures', 55, 120.00, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Clonazepam.png'),
(45, 'Phenobarbital', 'Luminal', 'Long-acting barbiturate', 50, 106.50, '2026-12-31', '2025-06-10 18:04:38', 5, NULL, 'Phenobarbital.png'),
(46, 'Haloperidol', 'Haldol', 'Typical antipsychotic', 80, 103.50, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Haloperidol.png'),
(47, 'Risperidone', 'Risperdal', 'Atypical antipsychotic', 75, 117.00, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Risperidone.png'),
(48, 'Olanzapine', 'Zyprexa', 'Atypical antipsychotic', 70, 126.00, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Olanzapine.png'),
(49, 'Quetiapine', 'Seroquel', 'Treats schizophrenia and bipolar', 65, 138.00, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Quetiapine.png'),
(50, 'Aripiprazole', 'Abilify', 'Atypical antipsychotic', 60, 157.50, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Aripiprazole.png'),
(51, 'Clozapine', 'Clozaril', 'Used in treatment-resistant schizophrenia', 55, 180.00, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Clozapine.png'),
(52, 'Chlorpromazine', 'Thorazine', 'First-generation antipsychotic', 50, 97.50, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Chlorpromazine.png'),
(53, 'Ziprasidone', 'Geodon', 'Second-generation antipsychotic', 45, 136.50, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Ziprasidone.png'),
(54, 'Lurasidone', 'Latuda', 'Atypical antipsychotic', 40, 165.00, '2026-12-31', '2025-06-10 18:04:38', 6, NULL, 'Lurasidone.png'),
(55, 'Acyclovir', 'Zovirax', 'Antiviral for herpes viruses', 100, 97.50, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Acyclovir.png'),
(56, 'Oseltamivir (Tamiflu)', 'Tamiflu', 'Treats influenza', 90, 131.25, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Oseltamivir.png'),
(57, 'Valacyclovir', 'Valtrex', 'Antiviral for herpes and shingles', 80, 118.50, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Valacyclovir.png'),
(58, 'Remdesivir', 'Veklury', 'Antiviral for COVID-19', 70, 375.00, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Remdesivir.png'),
(59, 'Zidovudine (AZT)', 'Retrovir', 'HIV antiretroviral', 60, 153.75, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Zidovudine.png'),
(60, 'Sofosbuvir', 'Sovaldi', 'Used to treat hepatitis C', 50, 450.00, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Sofosbuvir.png'),
(61, 'Lamivudine', 'Epivir', 'HIV and hepatitis B treatment', 45, 141.00, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Lamivudine.png'),
(62, 'Abacavir', 'Ziagen', 'HIV medication', 40, 176.25, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Abacavir.png'),
(63, 'Tenofovir', 'Viread', 'Antiretroviral for HIV', 35, 187.50, '2026-12-31', '2025-06-10 18:04:38', 7, NULL, 'Tenofovir.png'),
(64, 'Warfarin', 'Coumadin', 'Vitamin K antagonist', 120, 63.00, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Warfarin.png'),
(65, 'Heparin', 'Heparin Sodium', 'Injectable anticoagulant', 110, 76.50, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Heparin.png'),
(66, 'Enoxaparin', 'Lovenox', 'Low molecular weight heparin', 100, 123.75, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Enoxaparin.png'),
(67, 'Dabigatran', 'Pradaxa', 'Direct thrombin inhibitor', 90, 142.50, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Dabigatran.png'),
(68, 'Apixaban', 'Eliquis', 'Factor Xa inhibitor', 80, 150.00, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Apixaban.png'),
(69, 'Rivaroxaban', 'Xarelto', 'Oral anticoagulant', 70, 157.50, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Rivaroxaban.png'),
(70, 'Fondaparinux', 'Arixtra', 'Synthetic anticoagulant', 60, 165.00, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Fondaparinux.png'),
(71, 'Edoxaban', 'Savaysa', 'Oral anticoagulant', 50, 148.50, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Edoxaban.png'),
(72, 'Aspirin', 'Ecotrin', 'Low-dose antiplatelet', 200, 37.50, '2026-12-31', '2025-06-10 18:04:38', 8, NULL, 'Aspirin.png'),
(73, 'Diphenhydramine', 'Benadryl', 'First-generation antihistamine', 150, 60.00, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Diphenhydramine.png'),
(74, 'Loratadine', 'Claritin', 'Second-generation antihistamine', 140, 75.00, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Loratadine.png'),
(75, 'Cetirizine', 'Zyrtec', 'Non-drowsy antihistamine', 130, 82.50, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Cetirizine.png'),
(76, 'Fexofenadine', 'Allegra', 'Second-generation antihistamine', 120, 90.00, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Fexofenadine.png'),
(77, 'Chlorpheniramine', 'Chlor-Trimeton', 'First-gen antihistamine', 110, 56.25, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Chlorpheniramine.png'),
(78, 'Hydroxyzine', 'Vistaril', 'Anxiety and allergy treatment', 100, 97.50, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Hydroxyzine.png'),
(79, 'Levocetirizine', 'Xyzal', 'Non-sedating antihistamine', 90, 88.50, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Levocetirizine.png'),
(80, 'Desloratadine', 'Clarinex', 'Long-acting antihistamine', 80, 93.75, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Desloratadine.png'),
(81, 'Promethazine', 'Phenergan', 'Antihistamine for nausea/allergy', 70, 74.25, '2026-12-31', '2025-06-10 18:04:38', 9, NULL, 'Promethazine.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Paid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `userID`, `full_name`, `contact`, `address`, `total_amount`, `payment_method`, `payment_id`, `status`, `created_at`) VALUES
(1, 16, 'username', '0963214587', 'STB', 8.49, 'PayPal', '6H548197KF893060D', 'Paid', '2026-01-09 20:59:41'),
(2, 16, 'username', '0963214587', 'STB', 8.49, 'PayPal', '3SM611415K803024H', 'Paid', '2026-01-09 21:02:20'),
(3, 16, 'username', '0963214587', 'STB', 4.50, 'PayPal', '1T92535858030654E', 'Paid', '2026-01-09 21:25:44'),
(4, 16, 'username', '0963214587', 'STB', 8.49, 'PayPal', '6JU74411P70285532', 'Paid', '2026-01-09 22:21:30'),
(5, 16, 'username', '0963214587', 'STB', 19.99, 'PayPal', '46J47509LL950943B', 'Paid', '2026-01-09 23:54:27'),
(6, 16, 'username', '0963214587', 'STB', 19.99, 'PayPal', '4N07106976903050M', 'Paid', '2026-01-09 23:54:57'),
(7, 16, 'username', '0963214587', 'STB', 19.99, 'PayPal', '0C006589CC5135846', 'Paid', '2026-01-09 23:57:25'),
(9, 16, 'username', '0963214587', 'STB', 8.49, 'PayPal', '9G6864901M0276154', 'Paid', '2026-01-10 00:39:25'),
(10, 16, 'username', '0963214587', 'STB', 8.49, 'PayPal', '4XR57640LV5745015', 'Paid', '2026-01-10 00:47:22'),
(11, 36, 'piyoya', '0963214587', 'STB', 9.98, 'PayPal', '1E488004D52017442', 'Paid', '2026-01-10 00:49:26'),
(12, 36, 'piyoya', '0963214587', 'STB', 16.23, 'PayPal', '00M027551B240343B', 'Paid', '2026-01-10 00:53:21'),
(13, 36, 'piyoya', '0963214587', 'STB', 19.99, 'PayPal', '7E812691C8039315E', 'Paid', '2026-01-10 00:57:57'),
(14, 36, 'piyoya', '0963214587', 'STB', 19.99, 'PayPal', '88N27501DU513492F', 'Paid', '2026-01-10 01:00:01'),
(15, 36, 'piyoya', '0963214587', 'STB', 19.99, 'PayPal', '0LM280816R2659827', 'Paid', '2026-01-10 01:05:53'),
(16, 36, 'piyoya', '0963214587', 'STB', 13.25, 'PayPal', '5Y324443CS170805V', 'Paid', '2026-01-10 01:09:11'),
(17, 36, 'piyoya', '0963214587', 'STB', 19.99, 'PayPal', '5SU83442S6241642F', 'Paid', '2026-01-10 01:15:05'),
(18, 37, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 9.98, 'PayPal', '02F09197RE568064U', 'Paid', '2026-01-10 10:31:53'),
(19, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 17.95, 'PayPal', '9W141737AX208094X', 'Paid', '2026-01-10 10:53:49'),
(20, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 17.95, 'PayPal', '52F53325RT871220D', 'Paid', '2026-01-10 11:04:28'),
(21, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 17.95, 'PayPal', '3YU57853W74081820', 'Paid', '2026-01-10 11:52:33'),
(22, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 17.95, 'PayPal', '6DU32340PU437053N', 'Paid', '2026-01-10 11:58:58'),
(23, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 17.95, 'PayPal', '94J02018B5929403P', 'Paid', '2026-01-10 12:25:08'),
(24, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 9.98, 'PayPal', '0GU12553GY401100F', 'Paid', '2026-01-10 12:27:22'),
(25, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 19.99, 'PayPal', '8MF49125XS069154E', 'Paid', '2026-01-10 12:29:57'),
(26, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 3.99, 'PayPal', '3C729470PJ0190838', 'Paid', '2026-01-10 12:31:38'),
(27, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 12.99, 'PayPal', '1XY18557N3509354L', 'Paid', '2026-01-10 12:36:38'),
(28, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 12.99, 'PayPal', '34L64215TW451752W', 'Paid', '2026-01-10 12:37:26'),
(29, 38, 'piyoya', '09166314087', 'Altura Bata, Tanauan City', 3.99, 'PayPal', '45T768888H640421U', 'Paid', '2026-01-10 18:29:22'),
(30, 39, 'asdfg', '09166314087', 'Altura Bata, Tanauan City', 12.99, 'PayPal', '94218865N68530155', 'Paid', '2026-01-12 09:57:40'),
(31, 40, 'jerome', '09664685409', 'STB', 12.99, 'PayPal', '0P693173TA205525C', 'Paid', '2026-01-12 10:17:49'),
(32, 40, 'jerome', '09664685409', 'STB', 12.99, 'PayPal', '2FX62539H2723772H', 'Paid', '2026-01-12 10:19:28'),
(33, 40, 'jerome', '09664685409', 'STB', 12.99, 'PayPal', '8A0648296M384835B', 'Paid', '2026-01-12 10:20:52'),
(34, 40, 'jerome', '09166314087', 'STB', 12.99, 'PayPal', '6YC091232T652041W', 'Paid', '2026-01-12 10:24:16'),
(35, 40, 'jerome', '09664685409', 'STB', 12.99, 'PayPal', '9X935914E6212082B', 'Paid', '2026-01-12 10:26:58'),
(36, 38, 'piyoya', '09166314087', 'STB', 7.00, 'PayPal', '84B21399E7093143B', 'Paid', '2026-01-12 10:51:15'),
(37, 38, 'piyoya', '09166314087', 'STB', 7.00, 'PayPal', '1UL57592W8975614V', 'Paid', '2026-01-12 10:52:55'),
(38, 38, 'piyoya', '09166314087', 'STB', 12.99, 'PayPal', '7BY760322G858861A', 'Paid', '2026-01-12 11:35:56'),
(39, 38, 'piyoya', '09664685409', 'STB', 12.99, 'PayPal', '02F95804CW410590W', 'Paid', '2026-01-12 11:37:28'),
(40, 38, 'piyoya', '09166314087', 'STB', 8.75, 'PayPal', '155389756F130482L', 'Paid', '2026-01-12 12:00:04'),
(41, 38, 'piyoya', '09166314087', 'STB', 7.00, 'PayPal', '3G0006464A052970E', 'Paid', '2026-01-12 12:13:09'),
(42, 38, 'piyoya', '09166314087', 'STB', 8.75, 'PayPal', '83X259189U7652432', 'Paid', '2026-01-12 12:36:27'),
(43, 38, 'piyoya', '09166314087', 'STB', 8.75, 'PayPal', '6AW801904S486760S', 'Paid', '2026-01-12 12:37:42'),
(44, 38, 'piyoya', '09166314087', 'STB', 8.75, 'PayPal', '3NG54217LS1864323', 'Paid', '2026-01-12 12:38:46'),
(45, 38, 'piyoya', '09166314087', 'STB', 12.99, 'PayPal', '17K80603MX368013G', 'Paid', '2026-01-12 12:39:55'),
(46, 38, 'piyoya', '09166314087', 'STB', 12.99, 'PayPal', '6AD65785W0764674R', 'Paid', '2026-01-12 12:42:58'),
(47, 38, 'piyoya', '09166314087', 'STB', 12.99, 'PayPal', '07P33290BG079514E', 'Paid', '2026-01-12 12:45:33'),
(48, 38, 'piyoya', '09166314087', 'STB', 9.20, 'PayPal', '6LH84148KP066172M', 'Paid', '2026-01-12 12:48:34'),
(49, 38, 'piyoya', '09166314087', 'STB', 12.99, 'PayPal', '79A439094W500802L', 'Paid', '2026-01-12 12:52:51'),
(50, 38, 'piyoya', '09166314087', 'STB', 7.00, 'PayPal', '1NU61926SR438551A', 'Paid', '2026-01-12 12:58:32'),
(51, 38, 'piyoya', '09166314087', 'STB', 7.00, 'PayPal', '5W280500FU494741P', 'Paid', '2026-01-12 13:01:46'),
(52, 38, 'piyoya', '09166314087', 'STB', 3.99, 'PayPal', '4A677862CY6511256', 'Paid', '2026-01-12 18:19:05'),
(53, 38, 'piyoya', '09166314087', 'STB', 12.99, 'PayPal', '34V075858M906035E', 'Paid', '2026-01-12 18:21:10'),
(54, 38, 'piyoya', '09166314087', 'STB', 12.99, 'PayPal', '5KX405703T650515F', 'Paid', '2026-01-12 18:22:42'),
(55, 38, 'piyoya', '09166314087', 'STB', 105.00, 'PayPal', '3RP533219L719261G', 'Paid', '2026-01-13 05:48:35'),
(56, 38, 'piyoya', '09166314087', 'STB', 59.85, 'PayPal', '4VU77608U8227381R', 'Paid', '2026-01-13 07:41:23'),
(57, 38, 'piyoya', '09166314087', 'STB', 59.85, 'PayPal', '32K87388JW9411225', 'Paid', '2026-01-13 07:54:38'),
(58, 38, 'piyoya', '09166314087', 'STB', 67.50, 'PayPal', '3FH077221U7969024', 'Paid', '2026-01-13 08:34:08'),
(59, 38, 'piyoya', '09166314087', 'STB', 194.85, 'PayPal', '8D0103923Y186335M', 'Paid', '2026-01-13 08:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `medicine_id`, `price`, `quantity`) VALUES
(1, 1, 2, 4.50, 1),
(2, 1, 3, 3.99, 1),
(3, 2, 2, 4.50, 1),
(4, 2, 3, 3.99, 1),
(5, 3, 2, 4.50, 1),
(6, 4, 3, 3.99, 1),
(7, 4, 2, 4.50, 1),
(8, 5, 6, 12.99, 1),
(9, 5, 5, 7.00, 1),
(10, 6, 5, 7.00, 1),
(11, 6, 6, 12.99, 1),
(12, 7, 5, 7.00, 1),
(13, 7, 6, 12.99, 1),
(15, 9, 2, 4.50, 1),
(16, 9, 3, 3.99, 1),
(17, 10, 2, 4.50, 1),
(18, 10, 3, 3.99, 1),
(19, 11, 1, 5.99, 1),
(20, 11, 3, 3.99, 1),
(21, 12, 4, 6.25, 1),
(22, 12, 1, 5.99, 1),
(23, 12, 3, 3.99, 1),
(24, 13, 5, 7.00, 1),
(25, 13, 6, 12.99, 1),
(26, 14, 6, 12.99, 1),
(27, 14, 5, 7.00, 1),
(28, 15, 5, 7.00, 1),
(29, 15, 6, 12.99, 1),
(30, 16, 4, 6.25, 1),
(31, 16, 5, 7.00, 1),
(32, 17, 5, 7.00, 1),
(33, 17, 6, 12.99, 1),
(34, 18, 3, 3.99, 1),
(35, 18, 1, 5.99, 1),
(36, 19, 8, 8.75, 1),
(37, 19, 9, 9.20, 1),
(38, 20, 8, 8.75, 1),
(39, 20, 9, 9.20, 1),
(40, 21, 9, 9.20, 1),
(41, 21, 8, 8.75, 1),
(42, 22, 8, 8.75, 1),
(43, 22, 9, 9.20, 1),
(44, 23, 8, 8.75, 1),
(45, 23, 9, 9.20, 1),
(46, 24, 3, 3.99, 1),
(47, 24, 1, 5.99, 1),
(48, 25, 6, 12.99, 1),
(49, 25, 5, 7.00, 1),
(50, 26, 3, 3.99, 1),
(51, 27, 6, 12.99, 1),
(52, 28, 6, 12.99, 1),
(53, 29, 3, 3.99, 1),
(54, 30, 6, 12.99, 1),
(55, 31, 6, 12.99, 1),
(56, 32, 6, 12.99, 1),
(57, 33, 6, 12.99, 1),
(58, 34, 6, 12.99, 1),
(59, 35, 6, 12.99, 1),
(60, 36, 5, 7.00, 1),
(61, 37, 5, 7.00, 1),
(62, 38, 6, 12.99, 1),
(63, 39, 6, 12.99, 1),
(64, 40, 8, 8.75, 1),
(65, 41, 5, 7.00, 1),
(66, 42, 8, 8.75, 1),
(67, 43, 8, 8.75, 1),
(68, 44, 8, 8.75, 1),
(69, 45, 6, 12.99, 1),
(70, 46, 6, 12.99, 1),
(71, 47, 6, 12.99, 1),
(72, 48, 9, 9.20, 1),
(73, 49, 6, 12.99, 1),
(74, 50, 5, 7.00, 1),
(75, 51, 5, 7.00, 1),
(76, 52, 3, 3.99, 1),
(77, 53, 6, 12.99, 1),
(78, 54, 6, 12.99, 1),
(79, 55, 5, 105.00, 1),
(80, 56, 3, 59.85, 1),
(81, 57, 3, 59.85, 1),
(82, 58, 2, 67.50, 1),
(83, 59, 6, 194.85, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `sale_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `user_id`, `medicine_id`, `quantity`, `total_price`, `sale_date`) VALUES
(17, 2, 11, 10, 55.00, '2025-06-24 11:43:28'),
(18, 2, 12, 10, 62.00, '2025-06-24 11:43:28'),
(19, 2, 8, 1, 8.75, '2025-06-25 15:12:45'),
(20, 2, 9, 1, 9.20, '2025-06-25 15:12:45'),
(21, 2, 6, 1, 12.99, '2025-06-25 15:12:45'),
(22, 16, 1, 1, 5.99, '2026-01-01 15:15:13'),
(23, 16, 2, 1, 4.50, '2026-01-01 15:15:13'),
(24, 16, 2, 1, 4.50, '2026-01-07 00:27:37'),
(25, 16, 3, 1, 3.99, '2026-01-07 00:27:37');

-- --------------------------------------------------------

--
-- Table structure for table `sms_incoming`
--

CREATE TABLE `sms_incoming` (
  `id` int(11) NOT NULL,
  `sender` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `received_at` datetime NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sms_incoming`
--

INSERT INTO `sms_incoming` (`id`, `sender`, `message`, `received_at`, `order_id`, `payment_id`) VALUES
(25, '+639166314087', 'Your transaction was successful. Thank you for using our service.', '2026-01-13 09:35:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `email`, `address`, `category_id`, `created_at`) VALUES
(1, 'HealthPro Distributors', '09171234567', 'contact@healthpro.com', '123 Medical St., QC', 1, '2025-06-10 18:15:54'),
(2, 'PharmaLink Inc.', '09998887766', 'info@pharmalink.ph', '456 Wellness Ave., Makati', 4, '2025-06-10 18:15:54'),
(3, 'MediSupply Co.', '09223334455', 'support@medisupply.com', '789 Botika Blvd., Manila', 1, '2025-06-10 18:15:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userID`, `username`, `email`, `password`, `address`, `contact`, `role`) VALUES
(1, 'admin', NULL, 'admin', 'admin\r\n', '09123456789', 'admin'),
(2, 'piyo', NULL, 'hoho', 'Altura Bata', '09123456789', 'user'),
(5, 'Pi', NULL, 'hoho', 'Altura Bata', '09123456789', 'user'),
(9, 'Jade', NULL, 'hehe', 'Altura Bata', '09166314087', 'user'),
(16, 'username', '', 'password', '', '', 'user'),
(38, 'piyoya', 'jadee.fiona4@gmail.com', 'piyoyayi', 'Altura Bata Tanauan city', '09166314087', 'user'),
(39, 'asdfg', 'doboluexwayzi@gmail.com', 'piyoyayi', 'Altura Bata Tanauan city', '09166314087', 'user'),
(40, 'jerome', 'jeromeacads@gmail.com', 'jirom', 'Elac', '09664685409', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `fk_sales_user` (`user_id`),
  ADD KEY `fk_sales_medicine` (`medicine_id`);

--
-- Indexes for table `sms_incoming`
--
ALTER TABLE `sms_incoming`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`order_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sms_incoming`
--
ALTER TABLE `sms_incoming`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `sms_incoming`
--
ALTER TABLE `sms_incoming`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sms_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
