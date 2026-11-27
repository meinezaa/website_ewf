-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: website_ewf
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `activity` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_activity_user` (`user_id`),
  CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'Status pendaftaran internship #1 diperbarui menjadi accepted.','127.0.0.1','2026-11-23 09:48:08'),(2,1,'Jadwal wawancara pendaftaran internship #2 diperbarui.','127.0.0.1','2026-11-25 05:19:14'),(3,1,'Status pendaftaran internship #2 diperbarui menjadi pending.','127.0.0.1','2026-11-25 05:19:25'),(4,1,'Status pendaftaran internship #2 diperbarui menjadi in_review.','127.0.0.1','2026-11-25 05:23:29'),(5,1,'Status pendaftaran internship #2 diperbarui menjadi accepted.','127.0.0.1','2026-11-25 05:30:27'),(6,1,'Jadwal wawancara pendaftaran staff #1 diperbarui.','127.0.0.1','2026-11-25 08:04:04'),(7,1,'Status pendaftaran staff #1 diperbarui menjadi in_review.','127.0.0.1','2026-11-25 08:04:19'),(8,2,'Login berhasil.','127.0.0.1','2026-11-25 23:15:45'),(9,2,'Logout.','127.0.0.1','2026-11-25 23:16:21'),(10,1,'Login berhasil.','127.0.0.1','2026-11-25 23:16:35'),(11,1,'Logout.','127.0.0.1','2026-11-25 23:17:14'),(12,1,'Login berhasil.','127.0.0.1','2026-11-25 23:17:23'),(13,1,'Status pendaftaran internship #2 diperbarui menjadi completed.','127.0.0.1','2026-11-25 23:17:42'),(14,1,'LoA dibuat untuk peserta magang Indira Prastika.','127.0.0.1','2026-11-25 23:26:52'),(15,1,'Logout.','127.0.0.1','2026-11-25 23:29:04'),(16,1,'Login berhasil.','127.0.0.1','2026-11-25 23:29:19'),(17,1,'Logout.','127.0.0.1','2026-11-25 23:29:26'),(18,2,'Login berhasil.','127.0.0.1','2026-11-25 23:29:34'),(19,2,'Logout.','127.0.0.1','2026-11-25 23:30:03'),(20,1,'Login berhasil.','127.0.0.1','2026-11-25 23:30:14'),(21,1,'Logout.','127.0.0.1','2026-11-25 23:30:41'),(22,1,'Login berhasil.','127.0.0.1','2026-11-25 23:31:08'),(23,1,'Logout.','127.0.0.1','2026-11-25 23:31:18'),(24,2,'Login berhasil.','127.0.0.1','2026-11-25 23:31:35'),(25,2,'Logout.','127.0.0.1','2026-11-25 23:31:43'),(26,1,'Login berhasil.','127.0.0.1','2026-11-25 23:31:53'),(27,1,'Logout.','127.0.0.1','2026-11-25 23:32:09'),(28,4,'Login berhasil.','127.0.0.1','2026-11-25 23:34:56'),(29,4,'Login berhasil.','127.0.0.1','2026-11-25 23:35:12'),(30,4,'Mengubah foto profil admin.','127.0.0.1','2026-11-25 23:36:51'),(31,4,'Memperbarui profil admin.','127.0.0.1','2026-11-25 23:37:01'),(32,4,'Memperbarui profil admin.','127.0.0.1','2026-11-25 23:37:34'),(33,4,'Memperbarui profil admin.','127.0.0.1','2026-11-25 23:37:38'),(34,4,'Memperbarui profil admin.','127.0.0.1','2026-11-25 23:37:43'),(35,4,'Logout.','127.0.0.1','2026-11-25 23:37:49'),(36,1,'Login berhasil.','127.0.0.1','2026-11-25 23:38:02'),(37,1,'Logout.','127.0.0.1','2026-11-25 23:38:15'),(38,1,'Login berhasil.','127.0.0.1','2026-11-25 23:38:48'),(39,1,'Logout.','127.0.0.1','2026-11-25 23:44:37'),(40,4,'Login berhasil.','127.0.0.1','2026-11-25 23:44:53'),(41,4,'Logout.','127.0.0.1','2026-11-25 23:46:05'),(42,1,'Login berhasil.','127.0.0.1','2026-11-25 23:46:19'),(43,1,'Login berhasil.','127.0.0.1','2026-11-25 23:46:35'),(44,1,'Sertifikat magang dibuat untuk peserta Indira Prastika.','127.0.0.1','2026-11-25 23:47:20'),(45,1,'Logout.','127.0.0.1','2026-11-25 23:47:40'),(46,1,'Login berhasil.','127.0.0.1','2026-11-25 23:48:08'),(47,1,'Logout.','127.0.0.1','2026-11-25 23:49:49'),(48,2,'Login berhasil.','127.0.0.1','2026-11-25 23:50:01'),(49,2,'Logout.','127.0.0.1','2026-11-25 23:50:41');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `career_application_tracking_events`
--

DROP TABLE IF EXISTS `career_application_tracking_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `career_application_tracking_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `application_type` varchar(20) NOT NULL,
  `application_id` bigint(20) unsigned NOT NULL,
  `event_type` varchar(32) NOT NULL,
  `from_status` varchar(32) DEFAULT NULL,
  `to_status` varchar(32) DEFAULT NULL,
  `interview_at` datetime DEFAULT NULL,
  `interview_information` text DEFAULT NULL,
  `attendance` varchar(20) DEFAULT NULL,
  `actor_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attendance_reason` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `career_application_tracking_events_actor_id_foreign` (`actor_id`),
  KEY `career_tracking_events_application_time_index` (`application_type`,`application_id`,`created_at`),
  CONSTRAINT `career_application_tracking_events_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `career_application_tracking_events`
--

LOCK TABLES `career_application_tracking_events` WRITE;
/*!40000 ALTER TABLE `career_application_tracking_events` DISABLE KEYS */;
INSERT INTO `career_application_tracking_events` VALUES (1,'internship',2,'application_submitted',NULL,'pending',NULL,NULL,NULL,NULL,'2026-11-24 21:33:58','2026-11-24 21:33:58',NULL),(2,'internship',2,'interview_scheduled',NULL,NULL,'2026-11-25 11:30:00','TRILLIUM LT 2',NULL,1,'2026-11-24 22:19:14','2026-11-24 22:19:14',NULL),(3,'internship',2,'status_changed','pending','in_review',NULL,NULL,NULL,1,'2026-11-24 22:23:29','2026-11-24 22:23:29',NULL),(4,'internship',2,'attendance_confirmed',NULL,NULL,NULL,NULL,'hadir',2,'2026-11-24 22:24:21','2026-11-24 22:24:21',NULL),(5,'internship',2,'attendance_confirmed',NULL,NULL,NULL,NULL,'hadir',2,'2026-11-24 22:24:24','2026-11-24 22:24:24',NULL),(6,'internship',2,'attendance_confirmed',NULL,NULL,NULL,NULL,'hadir',2,'2026-11-24 22:24:35','2026-11-24 22:24:35',NULL),(7,'internship',2,'attendance_confirmed',NULL,NULL,NULL,NULL,'hadir',2,'2026-11-24 22:26:30','2026-11-24 22:26:30',NULL),(8,'internship',2,'attendance_confirmed',NULL,NULL,NULL,NULL,'hadir',2,'2026-11-24 22:29:35','2026-11-24 22:29:35',NULL),(9,'internship',2,'attendance_confirmed',NULL,NULL,NULL,NULL,'hadir',2,'2026-11-24 22:29:39','2026-11-24 22:29:39',NULL),(10,'internship',2,'attendance_confirmed',NULL,NULL,NULL,NULL,'hadir',2,'2026-11-24 22:29:41','2026-11-24 22:29:41',NULL),(11,'internship',2,'status_changed','in_review','accepted',NULL,NULL,NULL,1,'2026-11-24 22:30:27','2026-11-24 22:30:27',NULL),(12,'staff',1,'application_submitted',NULL,'pending',NULL,NULL,NULL,3,'2026-11-25 01:02:18','2026-11-25 01:02:18',NULL),(13,'staff',1,'interview_scheduled',NULL,NULL,'2026-11-28 13:00:00','lt 2 ewf',NULL,1,'2026-11-25 01:04:04','2026-11-25 01:04:04',NULL),(14,'staff',1,'status_changed','pending','in_review',NULL,NULL,NULL,1,'2026-11-25 01:04:19','2026-11-25 01:04:19',NULL),(15,'internship',2,'status_changed','accepted','completed',NULL,NULL,NULL,1,'2026-11-25 23:17:42','2026-11-25 23:17:42',NULL);
/*!40000 ALTER TABLE `career_application_tracking_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `internship_registration_id` bigint(20) unsigned NOT NULL,
  `certificate_number` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `issued_by` bigint(20) unsigned DEFAULT NULL,
  `issued_at` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `internship_registration_id` (`internship_registration_id`),
  UNIQUE KEY `certificate_number` (`certificate_number`),
  KEY `issued_by` (`issued_by`),
  CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`internship_registration_id`) REFERENCES `internship_registrations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internship_registrations`
--

DROP TABLE IF EXISTS `internship_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internship_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) unsigned DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(40) NOT NULL,
  `university` varchar(180) DEFAULT NULL,
  `major` varchar(180) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','in_review','accepted','rejected','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_of_birth` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reference` varchar(24) DEFAULT NULL,
  `job_id` varchar(64) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `headline` varchar(180) DEFAULT NULL,
  `expected_salary` varchar(40) DEFAULT NULL,
  `availability` varchar(100) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `privacy_consent_at` timestamp NULL DEFAULT NULL,
  `experiences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`experiences`)),
  `education_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`education_details`)),
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `cv_path` varchar(255) DEFAULT NULL,
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `registration_type` varchar(80) DEFAULT NULL,
  `internship_major` varchar(150) DEFAULT NULL,
  `target_position` varchar(180) DEFAULT NULL,
  `certificate_path` varchar(255) DEFAULT NULL,
  `loa_path` varchar(255) DEFAULT NULL,
  `loa_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`loa_details`)),
  `interview_at` datetime DEFAULT NULL,
  `interview_information` text DEFAULT NULL,
  `interview_attendance` varchar(20) DEFAULT NULL,
  `interview_confirmed_at` datetime DEFAULT NULL,
  `certificate_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certificate_details`)),
  `interview_attendance_reason` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `internship_registrations_email_unique` (`email`),
  UNIQUE KEY `applicant_id` (`applicant_id`),
  UNIQUE KEY `internship_registrations_reference_unique` (`reference`),
  KEY `fk_internship_reviewer` (`reviewed_by`),
  CONSTRAINT `fk_internship_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_internship_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internship_registrations`
--

LOCK TABLES `internship_registrations` WRITE;
/*!40000 ALTER TABLE `internship_registrations` DISABLE KEYS */;
INSERT INTO `internship_registrations` VALUES (2,2,1,'Indira Prastika','prastikaindira95@gmail.com','2001-11-29','perempuan','Surabaya','089637488038','upnvjt','sifo',NULL,'2026-11-01','2026-11-30','career-applications/EWF-APP-JPC3ILU2UY/cv/ArN7dSVSe2sJ7pgNj9knkYrt9YjZK1rETi3SSArX.pdf','completed','2026-11-24 21:33:58','2026-11-25 23:47:20','2001-11-29','Surabaya','EWF-APP-JPC3ILU2UY','internship-3','UI/UX Designer','PT Equityworld Futures','Pelamar magang',NULL,NULL,'https://id.linkedin.com/','Praktik Kerja Lapangan (PKL) untuk jurusan Sistem Informasi pada bidang UI/UX Designer, periode 2026-11-01 sampai 2026-11-30.','2026-11-24 21:33:58','[]','[{\"level\":\"S1\",\"institution\":\"upnvjt\",\"major\":\"sifo\",\"status\":\"Sedang berlangsung\",\"start_year\":\"2024\",\"completion_year\":\"2015\",\"year\":null,\"gpa\":\"3.87\"}]','[\"komunikasi\"]','career-applications/EWF-APP-JPC3ILU2UY/cv/ArN7dSVSe2sJ7pgNj9knkYrt9YjZK1rETi3SSArX.pdf','{\"transcript\":\"career-applications\\/EWF-APP-JPC3ILU2UY\\/documents\\/Nbw0j7gn4icVdrxfNTSgVCZxHCqv2ZIQGJ51XQTj.jpg\",\"cover_letter\":\"career-applications\\/EWF-APP-JPC3ILU2UY\\/documents\\/NHsWu2dgBV78n7eHoJByBabHBROTCoSDsGm154uq.jpg\",\"portfolio\":\"career-applications\\/EWF-APP-JPC3ILU2UY\\/documents\\/u1m3fQEyKPPFBC0rgUV7Er0HXf54rhFIqrNNl9zr.jpg\",\"ktp\":\"career-applications\\/EWF-APP-JPC3ILU2UY\\/documents\\/qqYwLeMMJp14OkvAejzquSiSW5vU5GRx0XeAeXB5.jpg\",\"diploma\":\"career-applications\\/EWF-APP-JPC3ILU2UY\\/documents\\/c3ecna0oDLQO2j8C08ahydKsfU0d2eYwKyFzJBbj.jpg\"}','Praktik Kerja Lapangan (PKL)','Sistem Informasi','UI/UX Designer',NULL,NULL,'{\"letter_number\":\"EWF\\/LOA\\/20261126\\/XOJR9\",\"participants\":[{\"registration_id\":2,\"student_number\":\"051\",\"full_name\":\"Indira Prastika\",\"study_program\":\"Sistem Informasi\"}],\"school_name\":\"upnvjt\",\"faculty\":\"FIK\",\"internship_field\":\"UI\\/UX Designer\",\"company_name\":\"PT Equityworld Futures\",\"company_location\":\"Surabaya\",\"letter_city\":\"Surabaya\",\"period_start\":\"2026-11-01\",\"period_end\":\"2026-11-30\",\"work_days\":\"Senin - Jumat\",\"work_hours\":\"08.00 - 17.00 WIB\",\"letter_date\":\"2026-11-26\",\"letter_body\":\"Dengan ini menerangkan bahwa peserta tersebut benar mengikuti program magang di perusahaan kami pada periode yang tercantum di atas.\",\"signer_title\":\"Supervisor Magang\",\"signer_name\":\"Firsa\"}','2026-11-25 11:30:00','TRILLIUM LT 2','hadir','2026-11-25 05:29:41','{\"certificate_number\":\"EWF-CERT-NXYLPSLKZF\",\"full_name\":\"Indira Prastika\",\"company_name\":\"PT EQUITYWORLD FUTURES\",\"period_start\":\"2026-11-01\",\"period_end\":\"2026-11-30\",\"issued_at\":\"2026-11-26\",\"signer_title\":\"MENTOR MAGANG\",\"signer_name\":\"Firsa\"}',NULL);
/*!40000 ALTER TABLE `internship_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_11_16_000005_add_gender_to_users_table',1),(2,'2026_11_11_000002_add_profile_fields_to_users_table',2),(3,'2026_11_18_000002_add_profile_photo_path_to_users_table',3),(4,'2026_11_23_000000_add_profile_cover_path_to_users_table',4),(5,'2026_11_23_000001_add_department_to_users_table',5),(6,'2026_11_16_000003_move_career_applications_to_existing_registration_tables',6),(7,'2026_11_17_000000_add_issued_documents_to_internship_registrations',7),(8,'2026_11_18_000000_complete_existing_internship_registrations',8),(9,'2026_11_18_000001_add_internship_major_to_internship_registrations',9),(10,'2026_11_25_000001_add_interview_tracking_to_registrations',10),(11,'2026_11_25_000002_add_loa_details_to_internship_registrations',11),(13,'2026_11_26_000001_add_certificate_details_to_internship_registrations',12),(14,'2026_11_19_000000_add_remember_token_to_users_table',13),(15,'0001_01_01_000000_create_users_table',14),(16,'2026_11_26_000000_add_interview_attendance_reason',15),(17,'2026_11_23_000000_create_activity_logs_table',16),(18,'2026_11_16_000004_drop_career_applications_after_copy',17),(19,'2026_11_11_000001_create_users_table',18),(20,'2026_11_16_000007_create_roles_table_if_missing',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin'),(3,'Applicant'),(2,'HRD');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_registrations`
--

DROP TABLE IF EXISTS `staff_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) unsigned DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(40) NOT NULL,
  `education` varchar(180) DEFAULT NULL,
  `position` varchar(180) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','in_review','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_of_birth` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reference` varchar(24) DEFAULT NULL,
  `job_id` varchar(64) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `headline` varchar(180) DEFAULT NULL,
  `expected_salary` varchar(40) DEFAULT NULL,
  `availability` varchar(100) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `privacy_consent_at` timestamp NULL DEFAULT NULL,
  `experiences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`experiences`)),
  `education_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`education_details`)),
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `cv_path` varchar(255) DEFAULT NULL,
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `interview_at` datetime DEFAULT NULL,
  `interview_information` text DEFAULT NULL,
  `interview_attendance` varchar(20) DEFAULT NULL,
  `interview_confirmed_at` datetime DEFAULT NULL,
  `interview_attendance_reason` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_registrations_email_unique` (`email`),
  UNIQUE KEY `applicant_id` (`applicant_id`),
  UNIQUE KEY `staff_registrations_reference_unique` (`reference`),
  KEY `fk_staff_reviewer` (`reviewed_by`),
  CONSTRAINT `fk_staff_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_staff_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_registrations`
--

LOCK TABLES `staff_registrations` WRITE;
/*!40000 ALTER TABLE `staff_registrations` DISABLE KEYS */;
INSERT INTO `staff_registrations` VALUES (1,3,1,'Indira Prastika','indiraprastika05@gmail.com','2012-02-25','perempuan','Surabaya','089637488038','upnvjt','Asisten WPB','career-applications/EWF-APP-FYWSO8PSWG/cv/NsHqOJ9gv2vtHrDhmlA5K3vrVYKLk2K9FnSwK8L6.docx','in_review','2026-11-25 01:02:18','2026-11-25 01:04:19','2012-02-25','Surabaya','EWF-APP-FYWSO8PSWG','professional-2','Asisten WPB','PT Equityworld Futures','',NULL,NULL,'https://id.linkedin.com/','','2026-11-25 01:02:18','[{\"type\":null,\"company\":\"pt ewf\",\"position\":\"keuangan\",\"start_period\":null,\"end_period\":null,\"description\":\"menyusun laporan\"}]','[{\"level\":null,\"institution\":\"upnvjt\",\"major\":\"manajemen\",\"status\":null,\"start_year\":null,\"completion_year\":null,\"year\":\"2024\",\"gpa\":\"3.78\"}]','[\"komunikasi\"]','career-applications/EWF-APP-FYWSO8PSWG/cv/NsHqOJ9gv2vtHrDhmlA5K3vrVYKLk2K9FnSwK8L6.docx','{\"transcript\":\"career-applications\\/EWF-APP-FYWSO8PSWG\\/documents\\/tVE1nBKG6TsuL16JweVoZQafsi6mdwE9V3CHw8Fs.jpg\",\"portfolio\":\"career-applications\\/EWF-APP-FYWSO8PSWG\\/documents\\/vQeAFE4vyDpIpywc0lWLDrjcYKyJWsdMjAeX5M8H.jpg\",\"ktp\":\"career-applications\\/EWF-APP-FYWSO8PSWG\\/documents\\/ELj7K9gZNSNiDevsz66W2EY3DlH4ru2E8RdWiuKC.jpg\",\"diploma\":\"career-applications\\/EWF-APP-FYWSO8PSWG\\/documents\\/TqS28ltbtjmjHgXKk6UN8Xa7cKv07EhZbXY9bWKR.jpg\"}','2026-11-28 13:00:00','lt 2 ewf',NULL,NULL,NULL);
/*!40000 ALTER TABLE `staff_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gender` varchar(32) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `headline` varchar(180) DEFAULT NULL,
  `profile_photo_path` varchar(255) DEFAULT NULL,
  `profile_cover_path` varchar(255) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_roles` (`role_id`),
  CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,2,'HRD KECEH GALERI MAGANG EWF','hrdgalerimagang@gmail.com','$2y$12$tl/Ts4.x.iooE5XQl/qP9OPzL03aMnbLy2HQS9.m3Zscl/ntGO0Ei','active','2026-11-22 19:44:24','2026-11-23 00:47:53',NULL,NULL,'089637488038',NULL,NULL,'profile-photos/1/iJ3Qn4k16gmIvCf2XvviwgjWoTZrzOoJeaRy4gtq.png','profile-cover-photos/1/GQwUr77nLlR1fGumZIJSC2HaTDTlxWdnscqNva7X.webp','HRD',NULL),(2,3,'Indira Prastika','prastikaindira95@gmail.com','$2y$12$JQHb.anJegOLQz92630xz.hCd/CJ6KsnJVYL0SwtVGPB5jhTqZTXy','active','2026-11-23 02:17:33','2026-11-24 21:23:18',NULL,NULL,'089637488038',NULL,NULL,'profile-photos/2/Bj7C6Isf6m57Q0uYmfwDTrfvVCXYCCVdeDrsxpRg.png',NULL,NULL,NULL),(3,3,'Indira Prastika','indiraprastika05@gmail.com','$2y$12$HPO.5BYhxf03RcZWbqKc2eEMW0tkyFbxGxmdN0fHD9p6rVJHg972O','active','2026-11-25 00:58:08','2026-11-25 00:58:08',NULL,NULL,'089637488038',NULL,NULL,NULL,NULL,NULL,NULL),(4,1,'Admin EWF 1','adminewf@gmail.com','$2y$12$LNITil9CjqsNUzWBE1sWj.mxfOKTghDZZdVCTQ.9BZJsyUfmCcx7i','active','2026-11-26 06:34:18','2026-11-25 23:36:51',NULL,NULL,NULL,NULL,NULL,'profile-photos/4/tkXaSfC9nLbG64w45P1GJcuHquOBH8aK72bKemF5.png',NULL,NULL,NULL);
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

-- Dump completed on 2026-11-27 20:47:41
