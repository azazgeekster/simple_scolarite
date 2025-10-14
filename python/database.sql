-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: student_app
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.1

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
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` year NOT NULL,
  `end_date` year NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academic_years_label_unique` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_years`
--

LOCK TABLES `academic_years` WRITE;
/*!40000 ALTER TABLE `academic_years` DISABLE KEYS */;
INSERT INTO `academic_years` VALUES (1,'2015-2016',2015,2016,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(2,'2016-2017',2016,2017,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(3,'2017-2018',2017,2018,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(4,'2018-2019',2018,2019,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(5,'2019-2020',2019,2020,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(6,'2020-2021',2020,2021,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(7,'2021-2022',2021,2022,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(8,'2022-2023',2022,2023,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(9,'2023-2024',2023,2024,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(10,'2024-2025',2024,2025,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(11,'2025-2026',2025,2026,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(12,'2026-2027',2026,2027,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(13,'2027-2028',2027,2028,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(14,'2028-2029',2028,2029,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(15,'2029-2030',2029,2030,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(16,'2030-2031',2030,2031,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(17,'2031-2032',2031,2032,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(18,'2032-2033',2032,2033,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(19,'2033-2034',2033,2034,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(20,'2034-2035',2034,2035,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(21,'2035-2036',2035,2036,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(22,'2036-2037',2036,2037,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(23,'2037-2038',2037,2038,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(24,'2038-2039',2038,2039,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(25,'2039-2040',2039,2040,0,'2025-07-21 00:08:45','2025-07-21 00:08:45'),(26,'2040-2041',2040,2041,0,'2025-07-21 00:08:45','2025-07-21 00:08:45');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bac_info`
--

LOCK TABLES `bac_info` WRITE;
/*!40000 ALTER TABLE `bac_info` DISABLE KEYS */;
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
  `student_id` bigint unsigned NOT NULL,
  `document_id` smallint unsigned NOT NULL,
  `academic_year_id` smallint unsigned NOT NULL,
  `semester_id` tinyint unsigned DEFAULT NULL,
  `status` enum('PENDING','READY','PICKED','COMPLETED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
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
  KEY `demandes_student_id_foreign` (`student_id`),
  KEY `demandes_document_id_foreign` (`document_id`),
  KEY `demandes_academic_year_id_foreign` (`academic_year_id`),
  KEY `demandes_processed_by_foreign` (`processed_by`),
  CONSTRAINT `demandes_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  CONSTRAINT `demandes_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `demandes_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `admins` (`id`),
  CONSTRAINT `demandes_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandes`
--

LOCK TABLES `demandes` WRITE;
/*!40000 ALTER TABLE `demandes` DISABLE KEYS */;
INSERT INTO `demandes` VALUES (2,1,1,9,NULL,'PENDING','temporaire',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-22 00:48:17','2025-07-22 00:48:17');
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
INSERT INTO `departments` VALUES (1,'Informatique',NULL,NULL,1),(2,'Mathématiques',NULL,NULL,NULL),(3,'Physique',NULL,NULL,NULL);
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
  `label_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_fr` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_en` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,'bac','شهادة البكالوريا','Baccalauréat','Baccalaureate',NULL,NULL,NULL),(2,'attestation_reussite','شهادة النجاح','Attestation de réussite','Certificate of Success',NULL,NULL,NULL),(3,'attestation_scolarite','شهادة مدرسية','Attestation de scolarité','Enrollment Certificate',NULL,NULL,NULL),(4,'attestation_langue','شهادة اللغة','Attestation de langue','Language Certificate',NULL,NULL,NULL),(5,'attestation_classement','شهادة الترتيب','Attestation de classement','Ranking Certificate',NULL,NULL,NULL),(6,'releve_notes','كشف النقط','Relevé de notes','Transcript',NULL,NULL,NULL),(7,'carte_etudiant','بطاقة الطالب','Carte d\'étudiant','Student Card',NULL,NULL,NULL);
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
-- Table structure for table `family`
--

DROP TABLE IF EXISTS `family`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `family` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `proximite` enum('père','mère','époux(se)') COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cin` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `date_deces` date DEFAULT NULL,
  `profession` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `family_student_id_foreign` (`student_id`),
  CONSTRAINT `family_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `family`
--

LOCK TABLES `family` WRITE;
/*!40000 ALTER TABLE `family` DISABLE KEYS */;
/*!40000 ALTER TABLE `family` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filieres`
--

DROP TABLE IF EXISTS `filieres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filieres` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `label_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_fr` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filieres`
--

LOCK TABLES `filieres` WRITE;
/*!40000 ALTER TABLE `filieres` DISABLE KEYS */;
INSERT INTO `filieres` VALUES (1,' علوم الحاسوب','Génie Informatique','GI','licence',1,NULL,NULL,NULL),(2,NULL,'Mathématiques Appliquées','MA','licence',2,NULL,NULL,NULL),(3,NULL,'Physique Fondamentale','PH','licence',3,NULL,NULL,NULL),(4,NULL,'Licence Informatique','INFO-LIC','licence',NULL,NULL,'2025-07-21 10:23:35','2025-07-21 10:23:35');
/*!40000 ALTER TABLE `filieres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `handicaps`
--

DROP TABLE IF EXISTS `handicaps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `handicaps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `num_carte_handicap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handicap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rib` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assurance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `handicaps_student_id_foreign` (`student_id`),
  CONSTRAINT `handicaps_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `handicaps`
--

LOCK TABLES `handicaps` WRITE;
/*!40000 ALTER TABLE `handicaps` DISABLE KEYS */;
/*!40000 ALTER TABLE `handicaps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `higher_education`
--

DROP TABLE IF EXISTS `higher_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `higher_education` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `education_level` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `university_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialty_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diploma_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_of_obtention` year DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `higher_education_student_id_education_level_unique` (`student_id`,`education_level`),
  KEY `higher_education_education_level_year_of_obtention_index` (`education_level`,`year_of_obtention`),
  CONSTRAINT `higher_education_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `higher_education`
--

LOCK TABLES `higher_education` WRITE;
/*!40000 ALTER TABLE `higher_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `higher_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `higher_education_grades`
--

DROP TABLE IF EXISTS `higher_education_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `higher_education_grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `higher_education_id` bigint unsigned NOT NULL,
  `semester` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `average` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `higher_education_grades_higher_education_id_foreign` (`higher_education_id`),
  CONSTRAINT `higher_education_grades_higher_education_id_foreign` FOREIGN KEY (`higher_education_id`) REFERENCES `higher_education` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `higher_education_grades`
--

LOCK TABLES `higher_education_grades` WRITE;
/*!40000 ALTER TABLE `higher_education_grades` DISABLE KEYS */;
/*!40000 ALTER TABLE `higher_education_grades` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (48,'2014_10_12_000000_create_users_table',1),(49,'2014_10_12_100000_create_password_reset_tokens_table',1),(50,'2019_08_19_000000_create_failed_jobs_table',1),(51,'2019_12_14_000001_create_personal_access_tokens_table',1),(52,'2024_09_27_153709_create_permission_tables',1),(53,'2024_09_28_121037_create_students_table',1),(54,'2024_09_28_121042_create_admins_table',1),(55,'2024_10_05_163506_create_bac_info_table',1),(56,'2024_10_05_163506_create_handicaps_table',1),(57,'2025_03_15_014627_create_higher_education_table',1),(58,'2025_03_15_014634_create_higher_education_grades_table',1),(59,'2025_03_15_014647_create_departments_table',1),(60,'2025_03_15_014654_create_professors_table',1),(61,'2025_03_15_015335_add_head_id_to_departments_table',1),(62,'2025_03_15_015638_create_family_table',1),(63,'2025_03_15_192950_create_filieres_table',1),(64,'2025_05_10_212740_create_academic_years_table',1),(65,'2025_05_10_212919_create_student_academic_filiere_table',1),(66,'2025_05_11_165944_create_documents_table',1),(67,'2025_05_11_170839_create_semesters_table',1),(68,'2025_05_11_214057_create_demandes_table',1),(69,'2025_05_19_222420_create_modules_table',1),(70,'2025_05_21_002043_create_student_enrollments_table',1),(71,'2025_07_22_002504_add_semester_id_to_demandes_tables',2);
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
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_ar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filiere_id` smallint unsigned DEFAULT NULL,
  `year_in_program` tinyint unsigned DEFAULT NULL COMMENT 'Which year: 1, 2, 3...',
  `semester_id` tinyint unsigned NOT NULL,
  `professor_id` mediumint unsigned DEFAULT NULL,
  `prerequisite_id` mediumint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_code_unique` (`code`),
  KEY `modules_semester_id_foreign` (`semester_id`),
  KEY `modules_prerequisite_id_foreign` (`prerequisite_id`),
  KEY `modules_filiere_id_foreign` (`filiere_id`),
  KEY `modules_professor_id_foreign` (`professor_id`),
  CONSTRAINT `modules_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE SET NULL,
  CONSTRAINT `modules_prerequisite_id_foreign` FOREIGN KEY (`prerequisite_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `modules_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `professors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `modules_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'M854','Vero aut enim aspernatur.',NULL,4,1,1,NULL,NULL,'2025-07-21 10:25:49','2025-07-21 10:25:49'),(2,'M671','Sequi corporis vitae quis.',NULL,4,1,1,NULL,NULL,'2025-07-21 10:25:49','2025-07-21 10:25:49'),(3,'M958','Architecto est alias.',NULL,4,1,1,NULL,NULL,'2025-07-21 10:25:49','2025-07-21 10:25:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professors`
--

LOCK TABLES `professors` WRITE;
/*!40000 ALTER TABLE `professors` DISABLE KEYS */;
INSERT INTO `professors` VALUES (1,'Azzedine','Dliou',NULL,NULL,'Informatique',NULL,NULL,NULL);
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
-- Table structure for table `semesters`
--

DROP TABLE IF EXISTS `semesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semesters` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `semesters_label_unique` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semesters`
--

LOCK TABLES `semesters` WRITE;
/*!40000 ALTER TABLE `semesters` DISABLE KEYS */;
INSERT INTO `semesters` VALUES (1,'S1',NULL,NULL),(2,'S2',NULL,NULL),(3,'S3',NULL,NULL),(4,'S4',NULL,NULL),(5,'S5',NULL,NULL),(6,'S6',NULL,NULL),(7,'S7',NULL,NULL),(8,'S8',NULL,NULL);
/*!40000 ALTER TABLE `semesters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_academic_filiere`
--

DROP TABLE IF EXISTS `student_academic_filiere`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_academic_filiere` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_year_id` smallint unsigned NOT NULL,
  `filiere_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_academic_filiere_student_id_academic_year_id_unique` (`student_id`,`academic_year_id`),
  KEY `student_academic_filiere_filiere_id_foreign` (`filiere_id`),
  KEY `student_academic_filiere_academic_year_id_foreign` (`academic_year_id`),
  CONSTRAINT `student_academic_filiere_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_academic_filiere_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_academic_filiere_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_academic_filiere`
--

LOCK TABLES `student_academic_filiere` WRITE;
/*!40000 ALTER TABLE `student_academic_filiere` DISABLE KEYS */;
INSERT INTO `student_academic_filiere` VALUES (3,1,9,1,'2025-07-21 10:25:49','2025-07-21 10:25:49');
/*!40000 ALTER TABLE `student_academic_filiere` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_enrollments`
--

DROP TABLE IF EXISTS `student_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_year_id` smallint unsigned NOT NULL,
  `semester_id` tinyint unsigned NOT NULL,
  `etape` tinyint unsigned DEFAULT NULL,
  `module_id` mediumint unsigned NOT NULL,
  `status` enum('enrolled','passed','failed','re-enrolled') COLLATE utf8mb4_unicode_ci DEFAULT 'enrolled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_module_year` (`student_id`,`module_id`,`academic_year_id`),
  KEY `student_enrollments_module_id_foreign` (`module_id`),
  KEY `student_enrollments_academic_year_id_foreign` (`academic_year_id`),
  KEY `student_enrollments_semester_id_foreign` (`semester_id`),
  CONSTRAINT `student_enrollments_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_enrollments_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_enrollments_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_enrollments`
--

LOCK TABLES `student_enrollments` WRITE;
/*!40000 ALTER TABLE `student_enrollments` DISABLE KEYS */;
INSERT INTO `student_enrollments` VALUES (1,1,10,1,NULL,1,'enrolled','2025-07-21 10:25:49','2025-07-21 10:25:49'),(2,1,10,1,NULL,2,'enrolled','2025-07-21 10:25:49','2025-07-21 10:25:49'),(3,1,10,1,NULL,3,'enrolled','2025-07-21 10:25:49','2025-07-21 10:25:49');
/*!40000 ALTER TABLE `student_enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cne` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apogee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` enum('m','f') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel_urgence` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lieu_naissance_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationalite` char(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situation_familiale` enum('célibataire','marié','divorcé','veuf') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situation_professionnelle` enum('étudiant','salarié','chômeur','autre') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organisme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `adresse_ar` text COLLATE utf8mb4_unicode_ci,
  `ville_naissance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville_naissance_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_naissance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` char(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MA',
  `boursier` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `photo_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_email_unique` (`email`),
  UNIQUE KEY `students_cne_unique` (`cne`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'Ahmed','Benali','أحمد','بنعلي','ahmed.benali@gmail.com','$2y$10$dZTVSMuL2VVOypL/l7Czruhx76Mxsv.p.bsM4rBZbG8W3Ztaf/TU2','G123456789','20012345','AB123456','m','0612345678','0619876543','2000-05-15',NULL,NULL,'MA','célibataire','étudiant',NULL,'123 Rue Principale, Casablanca','123 شارع الرئيسي، الدار البيضاء','Casablanca','الدار البيضاء','Casablanca-Settat','Casablanca-Settat','20000','MA',1,1,NULL,'2025-07-21 11:05:01','2025-07-21 00:08:45','profile_pictures/9c21e577-bc0a-4ca6-83b7-d56094bc537e.jpg','2025-07-21 00:08:45','2025-07-26 15:11:05',NULL);
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

-- Dump completed on 2025-07-26 18:52:00
