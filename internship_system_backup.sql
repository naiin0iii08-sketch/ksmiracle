-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: internship_system
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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `admin_code` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `admin_code` (`admin_code`),
  CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,1,'ADM001','Admin','System'),(2,27,'ad01','admin','min');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internship_requests`
--

DROP TABLE IF EXISTS `internship_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internship_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `position` varchar(100) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('pending','advisor_approved','letter_issued','completed','cancelled') DEFAULT 'pending',
  `supervision_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `internship_requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internship_requests`
--

LOCK TABLES `internship_requests` WRITE;
/*!40000 ALTER TABLE `internship_requests` DISABLE KEYS */;
INSERT INTO `internship_requests` VALUES (1,2,'บริษัทหน่วยงานพัฒนางานจำกด','เด็กฝึกงาน','2026-04-17','2026-04-30','cancelled',NULL,'2026-04-17 06:59:28'),(2,1,'บริษัท กสิกรไทย จำกัด (มหาชน)','Software Developer Trainee','2026-06-01','2026-10-31','advisor_approved','','2026-04-17 07:17:55'),(3,2,'Garena Online (Thailand)','UX/UI Designer Intern','2026-07-15','2026-11-15','advisor_approved',NULL,'2026-04-17 07:17:55'),(4,1,'Agoda Services Co., Ltd.','Data Analyst Intern','2026-05-20','2026-09-20','completed','เก๋มาก','2026-04-17 07:17:55'),(5,2,'บริษัทหน่วยงานพัฒนางานจำกด','เด็กฝึกงาน','2026-04-26','2026-07-28','pending',NULL,'2026-04-17 08:39:31');
/*!40000 ALTER TABLE `internship_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_logs`
--

LOCK TABLES `login_logs` WRITE;
/*!40000 ALTER TABLE `login_logs` DISABLE KEYS */;
INSERT INTO `login_logs` VALUES (1,'admin2','::1','Failed','2026-04-15 07:00:45'),(2,'admin','::1','Success','2026-04-15 07:02:09'),(3,'admin','::1','Success','2026-04-15 07:08:31'),(4,'swu123','::1','Success','2026-04-15 07:08:51'),(5,'admin','::1','Success','2026-04-15 08:42:18'),(6,'swu123','::1','Failed','2026-04-17 06:46:34'),(7,'admin','::1','Success','2026-04-17 06:46:45'),(8,'swu123','::1','Failed','2026-04-17 06:47:07'),(9,'swu123','::1','Success','2026-04-17 06:47:11'),(10,'admin','::1','Success','2026-04-17 06:59:44'),(11,'swu123','::1','Success','2026-04-17 07:23:46'),(12,'admin','::1','Success','2026-04-17 07:32:12'),(13,'T002','::1','Success','2026-04-17 07:44:36'),(14,'T002','::1','Success','2026-04-17 08:00:30'),(15,'admin','::1','Success','2026-04-17 08:08:59'),(16,'T002','::1','Success','2026-04-17 08:26:30'),(17,'admin','::1','Success','2026-04-17 08:27:33'),(18,'swu123','::1','Success','2026-04-17 08:39:12'),(19,'admin','::1','Success','2026-04-17 08:39:49'),(20,'swu123','::1','Success','2026-04-21 09:47:05'),(21,'admin','::1','Success','2026-04-21 09:47:37');
/*!40000 ALTER TABLE `login_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_logs`
--

DROP TABLE IF EXISTS `status_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `comments` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `changed_by` (`changed_by`),
  CONSTRAINT `status_logs_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `internship_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `status_logs_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_logs`
--

LOCK TABLES `status_logs` WRITE;
/*!40000 ALTER TABLE `status_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `status_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `student_code` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `major` varchar(100) DEFAULT NULL,
  `gender` enum('ชาย','หญิง','อื่นๆ') DEFAULT NULL,
  `year` int(11) DEFAULT 1,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `student_code` (`student_code`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,1,'641010101','สมชาย','ใจดี','วิศวกรรมคอมพิวเตอร์','ชาย',3),(2,4,'62101010767','สมหญิง','ใจเด็ด','คณะแพทย์',NULL,1);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `staff_code` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `staff_code` (`staff_code`),
  CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (1,2,'T001','ดร.มานะ','ขยันยิ่ง','ภาควิชาวิศวกรรมคอมพิวเตอร์','อาจารย์ประจำภาควิชา'),(2,28,'T002','สุภาวดี','รุ่งส่าง','คณะแพทย์',NULL);
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','teacher') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'student01','$2y$10$vt/q5NXurwVkpCThFScSz.KtW1Fj7Z4j8zMwECxfxShnqMYPjB44a','student','student01@swu.ac.th','active','2026-04-14 05:16:00','2026-04-14 05:16:00'),(2,'teacher01','$2y$10$vt/q5NXurwVkpCThFScSz.KtW1Fj7Z4j8zMwECxfxShnqMYPjB44a','teacher','teacher01@swu.ac.th','active','2026-04-14 05:16:00','2026-04-14 05:16:00'),(3,'admin','$2y$10$VCRVyIminElnCLUmm4sn6uX6joZq8WERAwMGR5W99rGUXTGKKEubK','admin','admin@swu.ac.th','active','2026-04-14 05:24:47','2026-04-14 05:29:51'),(4,'swu123','$2y$10$8YaCT0RVeFTjRbgy6EEOXuuNfdba9i./oDaJLOI2g6HDnO6WlfENS','student','somyin@g.swu.ac.th','active','2026-04-15 05:35:28','2026-04-15 05:35:28'),(27,'admin2','$2y$10$KFsOW0.6OedVESXYo3SuB.EwS.MhzoFwzkktHuqmxfSthHxb4j6ou','admin','admin@g.swu.ac.th','active','2026-04-15 06:50:11','2026-04-15 06:50:11'),(28,'T002','$2y$10$xV8L2tPE4ikIoER8qtDZ8uFLXDAIwELPl2j6w8l3D7M8fQxHltmqe','teacher','phavidik@g.swu.ac.th','active','2026-04-17 07:44:21','2026-04-17 07:44:21');
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

-- Dump completed on 2026-04-25 15:51:18
