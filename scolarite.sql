-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: scolarite
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `academic_years`
--

DROP TABLE IF EXISTS `academic_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_years` (
  `start_year` year NOT NULL,
  `end_year` year DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`start_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_years`
--

LOCK TABLES `academic_years` WRITE;
/*!40000 ALTER TABLE `academic_years` DISABLE KEYS */;
INSERT INTO `academic_years` VALUES (2023,2024,0,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(2024,2025,1,'2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `academic_years` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Super Admin','admin@university.ma','$2y$10$uuHtL6JrDxhy0IZycprODuA9pwicp972cn2wgjK6mMqD1A608jGu2','2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,'Mohamed Alaoui','malaoui@university.ma','$2y$10$q6PVqE1AWGtDNLOUehd7JuY2Tu5VKlpoO0127fVVnnhC71twBax.6','2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bac_info`
--

DROP TABLE IF EXISTS `bac_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bac_info` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `type_bac` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `annee_bac` year NOT NULL,
  `code_serie_bac` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie_du_bac` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moyenne_generale` decimal(5,2) NOT NULL,
  `moyenne_arabe` decimal(5,2) DEFAULT NULL,
  `moyenne_francais` decimal(5,2) DEFAULT NULL,
  `moyenne_deuxieme_langue` decimal(5,2) DEFAULT NULL,
  `moyenne_sciences_physiques` decimal(5,2) DEFAULT NULL,
  `moyenne_des_maths` decimal(5,2) DEFAULT NULL,
  `moyenne_national` decimal(5,2) DEFAULT NULL,
  `deuxieme_langue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_bac` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_bac` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_archivage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bac_info_student_id_foreign` (`student_id`),
  CONSTRAINT `bac_info_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bac_info`
--

LOCK TABLES `bac_info` WRITE;
/*!40000 ALTER TABLE `bac_info` DISABLE KEYS */;
INSERT INTO `bac_info` VALUES (1,1,'Sciences Mathématiques',2023,'SM','Sciences Mathématiques A',16.50,NULL,NULL,NULL,17.50,18.00,15.75,'Anglais','Casablanca-Settat','Casablanca',NULL,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,2,'Sciences Physiques',2023,'PC','Sciences Physiques',15.25,NULL,NULL,NULL,16.50,16.00,14.50,'Français','Rabat-Salé-Kénitra','Rabat',NULL,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(3,3,'Sciences Mathématiques',2023,'SM','Sciences Mathématiques B',14.75,NULL,NULL,NULL,14.00,15.50,13.80,'Anglais','Fès-Meknès','Fès',NULL,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `bac_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `document_id` smallint unsigned NOT NULL,
  `academic_year` year NOT NULL,
  `semester` enum('S1','S2','S3','S4','S5','S6') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('PENDING','READY','PICKED','COMPLETED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `retrait_type` enum('temporaire','permanent') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_by` bigint unsigned DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `ready_at` timestamp NULL DEFAULT NULL,
  `must_return_by` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `extension_requested_at` timestamp NULL DEFAULT NULL,
  `extension_days` tinyint unsigned DEFAULT NULL,
  `collected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `demandes_reference_number_unique` (`reference_number`),
  KEY `demandes_document_id_foreign` (`document_id`),
  KEY `demandes_academic_year_foreign` (`academic_year`),
  KEY `demandes_processed_by_foreign` (`processed_by`),
  KEY `demandes_student_id_status_index` (`student_id`,`status`),
  CONSTRAINT `demandes_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academic_years` (`start_year`) ON DELETE RESTRICT,
  CONSTRAINT `demandes_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `demandes_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `admins` (`id`),
  CONSTRAINT `demandes_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandes`
--

LOCK TABLES `demandes` WRITE;
/*!40000 ALTER TABLE `demandes` DISABLE KEYS */;
INSERT INTO `demandes` VALUES (7,'REQ-2025-00001',1,6,2024,NULL,'PENDING',NULL,'temporaire',NULL,NULL,NULL,'2025-10-22 21:08:51',NULL,NULL,NULL,NULL,'2025-10-15 21:08:51','2025-10-15 21:08:51'),(10,'REQ-2025-00002',1,7,2024,'S1','PENDING',NULL,'permanent',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-10-15 21:46:56','2025-10-15 21:46:56'),(12,'REQ-2025-00003',1,7,2024,NULL,'PENDING',NULL,'permanent',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-10-15 22:03:32','2025-10-15 22:03:32'),(13,'REQ-2025-00004',1,6,2024,NULL,'PENDING',NULL,'temporaire',NULL,NULL,NULL,'2025-10-18 18:12:10',NULL,NULL,NULL,NULL,'2025-10-16 18:12:10','2025-10-16 18:12:10'),(14,'REQ-2025-00005',1,6,2024,NULL,'PENDING',NULL,'temporaire',NULL,NULL,NULL,'2025-10-25 13:03:37',NULL,NULL,NULL,NULL,'2025-10-23 13:03:37','2025-10-23 13:03:37');
/*!40000 ALTER TABLE `demandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `head_id` mediumint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_head_id_foreign` (`head_id`),
  CONSTRAINT `departments_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `professors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Informatique','2025-10-14 00:24:46','2025-10-14 00:24:46',1),(2,'Mathématiques','2025-10-14 00:24:46','2025-10-14 00:24:46',3),(3,'Physique','2025-10-14 00:24:46','2025-10-14 00:24:46',4);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_fr` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_en` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requires_return` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether this document needs to specify temporary/definitive retrait',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (6,'bac','Diplôme original du baccalauréat','شهادة الباكالوريا (الأصل)','BAC (Original)','High School Diploma (Original)',NULL,1,'2025-10-15 20:40:23','2025-10-15 20:40:23'),(7,'releve_notes','Relevé officiel des notes de l\'étudiant','كشف النقط','Relevé de notes','Transcript','documents/releve_notes.blade.php',0,'2025-10-15 20:40:23','2025-10-15 20:40:23'),(8,'attestation_scolarite','Document attestant l\'inscription de l\'étudiant','شهادة التسجيل','Attestation de scolarité','Certificate of Enrollment','documents/attestation_scolarite.blade.php',0,'2025-10-15 20:40:23','2025-10-15 20:40:23'),(9,'attestation_reussite','Document attestant la réussite de l\'étudiant','شهادة النجاح','Attestation de réussite','Certificate of Success','documents/attestation_reussite.blade.php',0,'2025-10-15 20:40:23','2025-10-15 20:40:23'),(10,'convention_stage','Convention tripartite pour stage','اتفاقية التدريب','Convention de stage','Internship Agreement','documents/convention_stage.blade.php',0,'2025-10-15 20:40:23','2025-10-15 20:40:23'),(11,'attestation_classement','Attestation de classement','شهادة الترتيب','Attestation de classement','Ranking Certificate',NULL,0,NULL,NULL);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filieres`
--

DROP TABLE IF EXISTS `filieres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filieres` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g., INFO-L, MATH-M, BIO-L',
  `label_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_fr` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_en` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('licence','master','doctorat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'licence',
  `department_id` tinyint unsigned DEFAULT NULL COMMENT 'Dept',
  `professor_id` mediumint unsigned DEFAULT NULL COMMENT 'Coordinateur',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `filieres_department_id_foreign` (`department_id`),
  KEY `filieres_professor_id_foreign` (`professor_id`),
  CONSTRAINT `filieres_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `filieres_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `professors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filieres`
--

LOCK TABLES `filieres` WRITE;
/*!40000 ALTER TABLE `filieres` DISABLE KEYS */;
INSERT INTO `filieres` VALUES (1,'INFO-L','الإجازة في المعلوميات','Licence en Informatique','Bachelor in Computer Science','licence',1,1,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,'MATH-L','الإجازة في الرياضيات','Licence en Mathématiques','Bachelor in Mathematics','licence',2,3,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(3,'PHYS-L','الإجازة في الفيزياء','Licence en Physique','Bachelor in Physics','licence',3,4,'2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `filieres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_09_27_153709_create_permission_tables',1),(6,'2024_09_28_121037_create_students_table',1),(7,'2024_09_28_121042_create_admins_table',1),(8,'2024_10_05_163506_create_bac_info_table',1),(9,'2025_03_15_014647_create_departments_table',1),(10,'2025_03_15_014654_create_professors_table',1),(11,'2025_03_15_015335_add_head_id_to_departments_table',1),(12,'2025_03_15_015638_create_family_table',1),(13,'2025_03_15_192950_create_filieres_table',1),(14,'2025_05_10_212740_create_academic_years_table',1),(15,'2025_05_10_212919_create_student_program_enrollments_table',1),(16,'2025_05_11_165944_create_documents_table',1),(17,'2025_05_11_214057_create_demandes_table',1),(18,'2025_05_19_222420_create_modules_table',1),(19,'2025_10_12_121412_create_student_semester_entrollments_table',1),(20,'2025_10_12_121850_create_student_module_entrollments_table',1),(21,'2025_10_12_122355_create_module_grades_table',1),(22,'2025_10_15_013107_add_return_to_documents_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_grades`
--

DROP TABLE IF EXISTS `module_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module_grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `module_enrollment_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `module_id` mediumint unsigned NOT NULL,
  `continuous_assessment` decimal(5,2) DEFAULT NULL COMMENT 'Contrôle Continu (CC) - usually 40%',
  `exam_grade` decimal(5,2) DEFAULT NULL COMMENT 'Final exam grade - usually 60%',
  `final_grade` decimal(5,2) DEFAULT NULL COMMENT 'Overall grade out of 20',
  `exam_session` enum('normal','rattrapage','validated') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `graded_date` date DEFAULT NULL,
  `graded_by` mediumint unsigned NOT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this the final, official grade?',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_enrollment_session` (`module_enrollment_id`,`exam_session`),
  KEY `module_grades_graded_by_foreign` (`graded_by`),
  KEY `module_grades_module_id_foreign` (`module_id`),
  KEY `module_grades_student_id_module_id_index` (`student_id`,`module_id`),
  CONSTRAINT `module_grades_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `professors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_grades_module_enrollment_id_foreign` FOREIGN KEY (`module_enrollment_id`) REFERENCES `student_module_enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_grades_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_grades`
--

LOCK TABLES `module_grades` WRITE;
/*!40000 ALTER TABLE `module_grades` DISABLE KEYS */;
INSERT INTO `module_grades` VALUES (1,1,1,1,15.70,11.60,13.24,'normal','2025-09-30',3,1,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,2,1,2,18.40,8.10,12.22,'normal','2025-09-14',1,1,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(3,3,1,1,15.80,10.80,12.80,'normal','2025-10-07',3,1,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(4,4,1,2,8.70,17.60,14.04,'normal','2025-10-06',1,1,NULL,'2025-10-14 00:24:47','2025-10-14 00:24:47');
/*!40000 ALTER TABLE `module_grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g., BM324, MATH101, INFO205',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filiere_id` smallint unsigned DEFAULT NULL,
  `year_in_program` tinyint unsigned DEFAULT NULL COMMENT 'Which year: 1, 2, 3...',
  `semester` enum('S1','S2','S3','S4','S5','S6') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc_percentage` tinyint unsigned NOT NULL DEFAULT '0' COMMENT 'Continuous assessment weight %',
  `exam_percentage` tinyint unsigned NOT NULL DEFAULT '100' COMMENT 'Final exam weight %',
  `professor_id` mediumint unsigned DEFAULT NULL COMMENT 'respo',
  `prerequisite_id` mediumint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_code_unique` (`code`),
  KEY `modules_filiere_id_foreign` (`filiere_id`),
  KEY `modules_prerequisite_id_foreign` (`prerequisite_id`),
  KEY `modules_professor_id_foreign` (`professor_id`),
  CONSTRAINT `modules_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `modules_prerequisite_id_foreign` FOREIGN KEY (`prerequisite_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `modules_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `professors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'INFO101','Introduction à la Programmation','مقدمة في البرمجة',1,1,'S1',40,60,1,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,'INFO102','Algorithmes et Structures de Données','الخوارزميات وبنيات المعطيات',1,1,'S1',40,60,2,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(3,'INFO201','Base de Donnees','قواعد البيانات',1,1,'S2',40,60,1,1,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(4,'MATH101','Analyse 1','التحليل 1',2,1,'S1',30,70,3,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(5,'MATH102','Algèbre Linéaire','الجبر الخطي',2,1,'S1',30,70,3,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professors`
--

DROP TABLE IF EXISTS `professors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professors` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `professors_email_unique` (`email`),
  KEY `professors_department_id_foreign` (`department_id`),
  CONSTRAINT `professors_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professors`
--

LOCK TABLES `professors` WRITE;
/*!40000 ALTER TABLE `professors` DISABLE KEYS */;
INSERT INTO `professors` VALUES (1,'Ahmed','Benali','a.benali@university.ma','0612345678','Intelligence Artificielle',1,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,'Fatima','Zahra','f.zahra@university.ma','0612345679','Réseaux Informatiques',1,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(3,'Hassan','Idrissi','h.idrissi@university.ma','0612345680','Analyse Mathématique',2,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(4,'Amina','Tazi','a.tazi@university.ma','0612345681','Physique Quantique',3,'2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `professors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_families`
--

DROP TABLE IF EXISTS `student_families`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_families` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `father_firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_cin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_birth_date` date DEFAULT NULL,
  `father_death_date` date DEFAULT NULL,
  `father_profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_cin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_birth_date` date DEFAULT NULL,
  `mother_death_date` date DEFAULT NULL,
  `mother_profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_cin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_death_date` date DEFAULT NULL,
  `handicap_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handicap_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handicap_card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_families_student_id_foreign` (`student_id`),
  CONSTRAINT `student_families_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_families`
--

LOCK TABLES `student_families` WRITE;
/*!40000 ALTER TABLE `student_families` DISABLE KEYS */;
INSERT INTO `student_families` VALUES (1,1,'Mohammed','Mansouri',NULL,NULL,NULL,'Ingénieur','Fatima','Alami',NULL,NULL,NULL,'Professeur',NULL,NULL,NULL,NULL,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,2,'Omar','Benkirane',NULL,NULL,NULL,'Médecin','Amina','Tazi',NULL,NULL,NULL,'Avocate',NULL,NULL,NULL,NULL,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46'),(3,3,'Rachid','Elkadi',NULL,NULL,NULL,'Commerçant','Khadija','Fassi',NULL,NULL,NULL,'Enseignante',NULL,NULL,NULL,NULL,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `student_families` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_module_enrollments`
--

DROP TABLE IF EXISTS `student_module_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_module_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `semester_enrollment_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `module_id` mediumint unsigned NOT NULL,
  `registration_year` year DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `attempt_number` tinyint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_semester_module` (`semester_enrollment_id`,`module_id`),
  KEY `student_module_enrollments_module_id_foreign` (`module_id`),
  KEY `student_module_enrollments_student_id_module_id_index` (`student_id`,`module_id`),
  KEY `student_module_enrollments_student_id_index` (`student_id`),
  CONSTRAINT `student_module_enrollments_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_module_enrollments_semester_enrollment_id_foreign` FOREIGN KEY (`semester_enrollment_id`) REFERENCES `student_semester_enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_module_enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_module_enrollments`
--

LOCK TABLES `student_module_enrollments` WRITE;
/*!40000 ALTER TABLE `student_module_enrollments` DISABLE KEYS */;
INSERT INTO `student_module_enrollments` VALUES (1,1,1,1,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL,1),(2,1,1,2,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL,1),(3,2,1,1,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL,1),(4,2,1,2,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL,1),(5,3,2,4,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL,1),(6,3,2,5,NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL,1);
/*!40000 ALTER TABLE `student_module_enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_program_enrollments`
--

DROP TABLE IF EXISTS `student_program_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_program_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `filiere_id` smallint unsigned NOT NULL,
  `academic_year` year NOT NULL,
  `registration_year` year NOT NULL,
  `year_in_program` tinyint unsigned DEFAULT NULL COMMENT '1=L1/M1, 2=L2/M2, 3=L3',
  `enrollment_status` enum('active','completed','failed','withdrawn','transferred_out','suspended') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Administrative notes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_year` (`student_id`,`academic_year`),
  KEY `student_program_enrollments_filiere_id_foreign` (`filiere_id`),
  KEY `student_program_enrollments_academic_year_foreign` (`academic_year`),
  KEY `stud_prog_enroll_idx` (`student_id`,`filiere_id`,`enrollment_status`),
  CONSTRAINT `student_program_enrollments_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academic_years` (`start_year`) ON DELETE RESTRICT,
  CONSTRAINT `student_program_enrollments_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_program_enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_program_enrollments`
--

LOCK TABLES `student_program_enrollments` WRITE;
/*!40000 ALTER TABLE `student_program_enrollments` DISABLE KEYS */;
INSERT INTO `student_program_enrollments` VALUES (1,1,1,2024,2024,1,'active','2024-09-15',NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL),(2,2,1,2024,2024,1,'active','2024-09-15',NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL),(3,3,2,2024,2024,1,'active','2024-09-15',NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL);
/*!40000 ALTER TABLE `student_program_enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_semester_enrollments`
--

DROP TABLE IF EXISTS `student_semester_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_semester_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_enrollment_id` bigint unsigned NOT NULL,
  `semester` enum('S1','S2','S3','S4','S5','S6') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enrollment_status` enum('registered','in_progress','completed','withdrawn') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registered',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_program_semester` (`program_enrollment_id`,`semester`),
  KEY `student_semester_enrollments_semester_index` (`semester`),
  CONSTRAINT `student_semester_enrollments_program_enrollment_id_foreign` FOREIGN KEY (`program_enrollment_id`) REFERENCES `student_program_enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_semester_enrollments`
--

LOCK TABLES `student_semester_enrollments` WRITE;
/*!40000 ALTER TABLE `student_semester_enrollments` DISABLE KEYS */;
INSERT INTO `student_semester_enrollments` VALUES (1,1,'S1','in_progress','2025-10-14 00:24:46','2025-10-14 00:24:46'),(2,1,'S2','registered','2025-10-14 00:24:46','2025-10-14 00:24:46'),(3,2,'S1','in_progress','2025-10-14 00:24:46','2025-10-14 00:24:46'),(4,2,'S2','registered','2025-10-14 00:24:46','2025-10-14 00:24:46'),(5,3,'S1','in_progress','2025-10-14 00:24:46','2025-10-14 00:24:46'),(6,3,'S2','registered','2025-10-14 00:24:46','2025-10-14 00:24:46');
/*!40000 ALTER TABLE `student_semester_enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cne` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apogee` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cin` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel_urgence` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lieu_naissance_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationalite` char(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situation_familiale` enum('Célibataire','Marié','Divorcé','Veuf') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situation_professionnelle` enum('Étudiant','Salarié','Chômeur','Autre') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `adresse_ar` text COLLATE utf8mb4_unicode_ci,
  `sexe` enum('M','F') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Maroc',
  `boursier` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_cne_unique` (`cne`),
  UNIQUE KEY `students_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'R123456789','Youssef','Mansouri','يوسف','المنصوري','y.mansouri@student.ma','$2y$10$iOWAoD1uZXoQ/aazWF7cce6CI8lSSgrF9c6oPPj1eDIQ/masZl0LS','20230001','AB123456','0661234567',NULL,'2005-03-15','Casablanca',NULL,'MA','Célibataire',NULL,'123 Rue Mohammed V, Casablanca',NULL,'M','Maroc',NULL,1,NULL,'2025-10-30 17:48:59','2025-10-14 00:24:46','students_avatars/607b48e6-72a9-443b-bbd4-9759314057f0.jpg','2025-10-14 00:24:46','2025-10-30 17:48:59',NULL),(2,'R987654321','Salma','Benkirane','سلمى','بنكيران','s.benkirane@student.ma','$2y$10$IFOi7djxjPDaiCDS/AmdnedwzDUVoYjRXvOkHVf4m4Q7Klpt4Qdu2','20230002','CD789012','0662345678',NULL,'2005-07-22','Rabat',NULL,'MA','Célibataire',NULL,'45 Avenue Hassan II, Rabat',NULL,'F','Maroc',1,0,NULL,NULL,'2025-10-14 00:24:46',NULL,'2025-10-14 00:24:46','2025-10-14 00:24:46',NULL),(3,'R456789123','Karim','Elkadi','كريم','القاضي','k.elkadi@student.ma','$2y$10$a2LK7oJHzNZLnpAQqGAN2O403NTqC4fx57sho1evSQQdqzPogWmK6','20230003','EF345678','0663456789',NULL,'2005-11-10','Fès',NULL,'MA','Célibataire',NULL,'78 Boulevard Mohammed VI, Fès',NULL,'M','Maroc',NULL,1,'C2dmH0glLO7uXqHE5D3ncapMhizc42YLoPNtaT7SXPxQQxWhcIWAbzK0Khak','2025-10-30 18:36:26','2025-10-30 18:16:38',NULL,'2025-10-14 00:24:46','2025-10-30 18:36:26',NULL);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31 19:36:58
