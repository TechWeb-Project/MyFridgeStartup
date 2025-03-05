-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: svuotafrigo
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_durata`
--

DROP TABLE IF EXISTS `categoria_durata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_durata` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_categoria` bigint unsigned NOT NULL,
  `id_durata` bigint unsigned NOT NULL,
  `durata_standard` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_durata_id_categoria_foreign` (`id_categoria`),
  KEY `categoria_durata_id_durata_foreign` (`id_durata`),
  CONSTRAINT `categoria_durata_id_categoria_foreign` FOREIGN KEY (`id_categoria`) REFERENCES `categorie` (`id_categoria`) ON DELETE CASCADE,
  CONSTRAINT `categoria_durata_id_durata_foreign` FOREIGN KEY (`id_durata`) REFERENCES `durata` (`id_durata`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_durata`
--

LOCK TABLES `categoria_durata` WRITE;
/*!40000 ALTER TABLE `categoria_durata` DISABLE KEYS */;
INSERT INTO `categoria_durata` VALUES (3,1,1,5,'2025-02-06 14:26:11','2025-02-06 14:26:11'),(4,1,1,5,'2025-02-06 14:28:13','2025-02-06 14:28:13'),(5,5,1,3,'2025-02-06 14:41:02','2025-02-06 14:41:02'),(6,4,1,3,'2025-02-06 15:13:05','2025-02-06 15:13:05'),(7,2,1,2,'2025-02-07 15:05:07','2025-02-07 15:05:07'),(8,11,2,30,'2025-02-18 12:39:54','2025-02-18 12:39:54'),(9,4,1,3,'2025-02-18 12:41:00','2025-02-18 12:41:00'),(10,7,1,3,'2025-02-18 12:45:57','2025-02-18 12:45:57'),(11,4,1,3,'2025-02-18 12:49:31','2025-02-18 12:49:31'),(12,5,3,3,'2025-02-18 13:03:17','2025-02-18 13:03:17'),(13,10,3,365,'2025-02-18 13:10:46','2025-02-18 13:10:46'),(14,3,1,2,'2025-02-18 13:20:07','2025-02-18 13:20:07'),(15,9,1,7,'2025-03-02 13:17:24','2025-03-02 13:17:24'),(16,9,2,7,'2025-03-02 13:17:47','2025-03-02 13:17:47'),(17,10,1,365,'2025-03-02 13:18:07','2025-03-02 13:18:07'),(18,1,1,5,'2025-03-02 14:01:40','2025-03-02 14:01:40'),(19,1,1,5,'2025-03-02 14:03:03','2025-03-02 14:03:03'),(20,8,1,3,'2025-03-02 14:03:38','2025-03-02 14:03:38'),(21,8,1,3,'2025-03-02 14:10:26','2025-03-02 14:10:26'),(22,1,1,5,'2025-03-02 14:13:06','2025-03-02 14:13:06'),(23,1,3,5,'2025-03-03 21:06:07','2025-03-03 21:06:07'),(24,7,1,3,'2025-03-03 22:52:57','2025-03-03 22:52:57'),(25,8,1,3,'2025-03-04 10:02:34','2025-03-04 10:02:34'),(26,2,1,2,'2025-03-04 13:00:34','2025-03-04 13:00:34'),(27,4,1,3,'2025-03-04 17:05:38','2025-03-04 17:05:38'),(28,1,1,5,'2025-03-04 17:05:43','2025-03-04 17:05:43'),(29,4,1,3,'2025-03-04 23:53:55','2025-03-04 23:53:55'),(30,4,1,3,'2025-03-04 23:53:56','2025-03-04 23:53:56'),(31,4,1,3,'2025-03-04 23:53:57','2025-03-04 23:53:57'),(32,4,1,3,'2025-03-04 23:53:57','2025-03-04 23:53:57'),(33,4,1,3,'2025-03-04 23:53:58','2025-03-04 23:53:58'),(34,4,1,3,'2025-03-04 23:53:58','2025-03-04 23:53:58'),(35,4,1,3,'2025-03-04 23:53:58','2025-03-04 23:53:58'),(36,4,1,3,'2025-03-04 23:53:59','2025-03-04 23:53:59'),(37,4,1,3,'2025-03-04 23:53:59','2025-03-04 23:53:59'),(38,4,1,3,'2025-03-04 23:53:59','2025-03-04 23:53:59'),(39,4,1,3,'2025-03-04 23:53:59','2025-03-04 23:53:59'),(40,4,1,3,'2025-03-04 23:54:00','2025-03-04 23:54:00'),(41,4,1,3,'2025-03-04 23:54:00','2025-03-04 23:54:00'),(42,4,1,3,'2025-03-04 23:54:00','2025-03-04 23:54:00'),(43,4,1,3,'2025-03-04 23:54:00','2025-03-04 23:54:00'),(44,4,1,3,'2025-03-04 23:54:01','2025-03-04 23:54:01'),(45,4,1,3,'2025-03-04 23:54:01','2025-03-04 23:54:01'),(46,4,1,3,'2025-03-04 23:54:01','2025-03-04 23:54:01'),(47,4,1,3,'2025-03-04 23:54:03','2025-03-04 23:54:03'),(48,4,1,3,'2025-03-04 23:54:05','2025-03-04 23:54:05'),(49,4,1,3,'2025-03-04 23:54:05','2025-03-04 23:54:05'),(50,4,1,3,'2025-03-04 23:54:06','2025-03-04 23:54:06'),(51,4,1,3,'2025-03-04 23:54:06','2025-03-04 23:54:06'),(52,4,1,3,'2025-03-04 23:54:06','2025-03-04 23:54:06'),(53,4,1,3,'2025-03-04 23:54:06','2025-03-04 23:54:06'),(54,4,1,3,'2025-03-04 23:54:07','2025-03-04 23:54:07'),(55,4,1,3,'2025-03-04 23:54:53','2025-03-04 23:54:53'),(56,1,1,5,'2025-03-05 08:47:37','2025-03-05 08:47:37'),(57,1,1,5,'2025-03-05 08:47:37','2025-03-05 08:47:37'),(58,1,1,5,'2025-03-05 08:47:37','2025-03-05 08:47:37'),(59,1,1,5,'2025-03-05 08:47:37','2025-03-05 08:47:37'),(60,1,1,5,'2025-03-05 08:47:38','2025-03-05 08:47:38'),(61,1,1,5,'2025-03-05 08:47:38','2025-03-05 08:47:38'),(62,1,1,5,'2025-03-05 08:47:38','2025-03-05 08:47:38'),(63,1,1,5,'2025-03-05 08:47:38','2025-03-05 08:47:38'),(64,1,1,5,'2025-03-05 08:47:38','2025-03-05 08:47:38'),(65,1,1,5,'2025-03-05 08:47:38','2025-03-05 08:47:38'),(66,1,1,5,'2025-03-05 08:47:39','2025-03-05 08:47:39'),(67,1,1,5,'2025-03-05 08:47:47','2025-03-05 08:47:47'),(68,1,1,5,'2025-03-05 08:47:47','2025-03-05 08:47:47'),(69,1,1,5,'2025-03-05 08:53:12','2025-03-05 08:53:12'),(70,1,1,5,'2025-03-05 08:53:12','2025-03-05 08:53:12'),(71,1,1,5,'2025-03-05 08:53:12','2025-03-05 08:53:12'),(72,1,1,5,'2025-03-05 08:53:12','2025-03-05 08:53:12'),(73,1,1,5,'2025-03-05 08:53:12','2025-03-05 08:53:12'),(74,1,1,5,'2025-03-05 08:53:13','2025-03-05 08:53:13'),(75,1,1,5,'2025-03-05 08:53:13','2025-03-05 08:53:13'),(76,1,1,5,'2025-03-05 08:53:13','2025-03-05 08:53:13'),(77,1,1,5,'2025-03-05 08:53:13','2025-03-05 08:53:13'),(78,1,1,5,'2025-03-05 08:53:13','2025-03-05 08:53:13'),(79,1,1,5,'2025-03-05 08:53:13','2025-03-05 08:53:13'),(80,1,1,5,'2025-03-05 08:53:14','2025-03-05 08:53:14'),(81,1,1,5,'2025-03-05 08:53:14','2025-03-05 08:53:14'),(82,1,1,5,'2025-03-05 08:53:14','2025-03-05 08:53:14'),(83,1,1,5,'2025-03-05 08:53:14','2025-03-05 08:53:14'),(84,1,1,5,'2025-03-05 14:03:11','2025-03-05 14:03:11'),(85,5,1,3,'2025-03-05 14:03:18','2025-03-05 14:03:18'),(86,8,1,3,'2025-03-05 14:03:27','2025-03-05 14:03:27'),(87,11,1,30,'2025-03-05 14:03:38','2025-03-05 14:03:38'),(88,4,1,3,'2025-03-05 14:03:53','2025-03-05 14:03:53'),(89,6,1,30,'2025-03-05 14:04:05','2025-03-05 14:04:05'),(90,14,1,7,'2025-03-05 14:04:18','2025-03-05 14:04:18'),(91,9,1,7,'2025-03-05 14:04:27','2025-03-05 14:04:27'),(92,12,1,30,'2025-03-05 14:04:36','2025-03-05 14:04:36'),(93,15,1,7,'2025-03-05 14:04:47','2025-03-05 14:04:47'),(94,3,1,2,'2025-03-05 14:05:02','2025-03-05 14:05:02'),(95,10,1,365,'2025-03-05 14:05:14','2025-03-05 14:05:14'),(96,7,1,3,'2025-03-05 14:05:26','2025-03-05 14:05:26'),(97,13,1,3,'2025-03-05 14:05:52','2025-03-05 14:05:52'),(98,2,1,2,'2025-03-05 14:06:06','2025-03-05 14:06:06');
/*!40000 ALTER TABLE `categoria_durata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorie` (
  `id_categoria` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `giorni_categoria` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorie`
--

LOCK TABLES `categorie` WRITE;
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
INSERT INTO `categorie` VALUES (1,'Latticino',5,'2025-02-06 10:32:03','2025-02-06 14:51:24'),(2,'Carne',2,'2025-02-06 10:32:03','2025-02-06 14:51:24'),(3,'Pesce',2,'2025-02-06 10:32:03','2025-02-06 14:51:24'),(4,'Frutta',3,'2025-02-06 10:32:03','2025-02-06 14:51:24'),(5,'Verdura',3,'2025-02-06 10:32:03','2025-02-06 14:51:24'),(6,'Cereale',30,'2025-02-06 10:32:03','2025-02-06 14:52:07'),(7,'Pane',3,'2025-02-06 10:32:03','2025-02-06 14:51:24'),(8,'Prodotto forno',3,'2025-02-06 10:32:03','2025-02-06 15:00:03'),(9,'Bevanda',7,'2025-02-06 10:32:03','2025-02-06 14:56:25'),(10,'Conserva',365,'2025-02-06 10:32:03','2025-02-06 14:56:25'),(11,'Condimento',30,'2025-02-06 10:32:03','2025-02-06 14:51:24'),(12,'Legume',30,'2025-02-06 10:32:03','2025-02-06 14:58:28'),(13,'Proteina vegetale',3,'2025-02-06 10:32:03','2025-02-06 14:59:09'),(14,'Dolce',7,'2025-02-06 10:32:03','2025-02-06 14:59:32'),(15,'Snack',7,'2025-02-06 10:32:03','2025-02-06 14:51:24');
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `durata`
--

DROP TABLE IF EXISTS `durata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `durata` (
  `id_durata` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome_durata` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `moltiplicatore_durata` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_durata`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `durata`
--

LOCK TABLES `durata` WRITE;
/*!40000 ALTER TABLE `durata` DISABLE KEYS */;
INSERT INTO `durata` VALUES (1,'Breve durata',1,'2025-02-06 10:43:41','2025-02-06 15:11:41'),(2,'Media durata',2,'2025-02-06 10:43:41','2025-02-06 15:11:46'),(3,'Lunga durata',15,'2025-02-06 10:43:41','2025-02-06 15:11:51');
/*!40000 ALTER TABLE `durata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `errors`
--

DROP TABLE IF EXISTS `errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `errors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `errors`
--

LOCK TABLES `errors` WRITE;
/*!40000 ALTER TABLE `errors` DISABLE KEYS */;
INSERT INTO `errors` VALUES (1,'Errore API','Failed to fetch','2025-02-23 16:49:17','2025-02-23 16:49:17'),(2,'Errore API','Failed to fetch','2025-02-24 12:07:59','2025-02-24 12:07:59'),(3,'Errore API','Failed to fetch','2025-02-24 12:08:00','2025-02-24 12:08:00'),(4,'Errore API','Failed to fetch','2025-02-24 12:08:01','2025-02-24 12:08:01'),(5,'Errore API','Failed to fetch','2025-03-03 22:12:00','2025-03-03 22:12:00'),(6,'Errore API','Failed to fetch','2025-03-03 22:12:01','2025-03-03 22:12:01'),(7,'Errore API','Failed to fetch','2025-03-03 22:12:01','2025-03-03 22:12:01'),(8,'Errore API','Failed to fetch','2025-03-03 22:12:01','2025-03-03 22:12:01'),(9,'Errore API','Failed to fetch','2025-03-03 22:12:01','2025-03-03 22:12:01'),(10,'Errore API','Failed to fetch','2025-03-03 22:12:01','2025-03-03 22:12:01'),(11,'Errore API','Failed to fetch','2025-03-03 22:12:01','2025-03-03 22:12:01'),(12,'Errore API','Failed to fetch','2025-03-03 22:12:02','2025-03-03 22:12:02'),(13,'Errore API','Failed to fetch','2025-03-03 22:24:28','2025-03-03 22:24:28'),(14,'Errore API','Failed to fetch','2025-03-03 22:24:30','2025-03-03 22:24:30'),(15,'Errore API','Failed to fetch','2025-03-03 22:27:13','2025-03-03 22:27:13'),(16,'Errore API','Failed to fetch','2025-03-03 22:28:10','2025-03-03 22:28:10'),(17,'Errore API','Failed to fetch','2025-03-03 22:28:10','2025-03-03 22:28:10'),(18,'Errore API','Failed to fetch','2025-03-03 22:28:10','2025-03-03 22:28:10');
/*!40000 ALTER TABLE `errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `frigo`
--

DROP TABLE IF EXISTS `frigo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `frigo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_prodotto` bigint unsigned NOT NULL,
  `id_user` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `frigo_id_prodotto_foreign` (`id_prodotto`),
  KEY `frigo_id_user_foreign` (`id_user`),
  CONSTRAINT `frigo_id_prodotto_foreign` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id_prodotto`) ON DELETE CASCADE,
  CONSTRAINT `frigo_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frigo`
--

LOCK TABLES `frigo` WRITE;
/*!40000 ALTER TABLE `frigo` DISABLE KEYS */;
INSERT INTO `frigo` VALUES (1,28,2),(2,29,2),(3,30,2),(4,31,2),(5,32,2),(6,33,2),(7,34,2),(8,35,2),(9,36,2),(10,37,2),(11,38,2),(12,39,2),(13,40,2),(14,41,2),(15,42,2);
/*!40000 ALTER TABLE `frigo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_21_172539_create_categorie_table',1),(5,'2025_01_21_172901_create_durata_table',1),(6,'2025_01_21_173136_create_categoria_durata_table',1),(7,'2025_01_21_185153_add_role_to_users_table',1),(8,'2025_01_21_192342_create_prodotto_table',1),(9,'2025_01_21_204848_create_frigo_table',1),(10,'2025_02_12_211634_restore_backup',2),(12,'2025_02_18_130258_update_prodotto_categoria_durata',3),(13,'2025_02_18_131131_add_unita_misura_to_prodotto',4),(14,'2025_02_05_141525_add_profile_image_to_users_table',5),(15,'2025_02_17_172908_create_errors_table',5),(16,'2025_02_17_173302_update_recipes_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `prodotto`
--

DROP TABLE IF EXISTS `prodotto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prodotto` (
  `id_prodotto` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome_prodotto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_scadenza` date NOT NULL,
  `quantita` int NOT NULL DEFAULT '1',
  `unita_misura` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pezzi',
  `id_categoria_durata` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_prodotto`),
  KEY `prodotto_id_categoria_durata_foreign` (`id_categoria_durata`),
  CONSTRAINT `prodotto_id_categoria_durata_foreign` FOREIGN KEY (`id_categoria_durata`) REFERENCES `categoria_durata` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prodotto`
--

LOCK TABLES `prodotto` WRITE;
/*!40000 ALTER TABLE `prodotto` DISABLE KEYS */;
INSERT INTO `prodotto` VALUES (3,'Waluigi','2025-04-18',1,'pezzi',5,'2025-02-06 14:41:02','2025-03-01 15:49:37'),(5,'Manzo','2025-02-28',1,'pezzi',7,'2025-02-07 15:05:07','2025-03-04 12:59:30'),(7,'Afragola','2025-03-29',3,'pezzi',9,'2025-02-18 12:41:00','2025-03-01 15:41:37'),(8,'Rabbit','2025-02-28',1,'pezzi',10,'2025-02-18 12:45:57','2025-03-03 23:25:12'),(10,'Carote','2025-04-04',500,'grammi',12,'2025-02-18 13:03:17','2025-03-04 10:11:10'),(11,'Prodotto infinito','2040-02-15',1,'pezzi',13,'2025-02-18 13:10:46','2025-02-18 13:10:46'),(15,'bevandad','2026-03-02',1,'pezzi',17,'2025-03-02 13:18:07','2025-03-02 13:18:07'),(16,'pizza','2025-03-07',3,'pezzi',18,'2025-03-02 14:01:40','2025-03-02 14:01:40'),(21,'RPIUREOFEIOFHEIOGFHEGOPEHJRFIOPESJFOIPEFJEIOPFJWEOPJFIOPWJFIOEWFJWIOPEJWOF','2025-05-17',1,'fette',23,'2025-03-03 21:06:07','2025-03-03 21:06:07'),(23,'step','2025-03-07',1,'fette',25,'2025-03-04 10:02:34','2025-03-04 10:02:34'),(24,'Petto di tacchino','2025-03-06',1,'pezzi',26,'2025-03-04 13:00:34','2025-03-04 13:00:34'),(25,'Petto di pollo','2025-03-07',2,'pezzi',27,'2025-03-04 17:05:38','2025-03-04 17:05:38'),(26,'Petto di pollo','2025-03-09',1,'pezzi',28,'2025-03-04 17:05:43','2025-03-04 17:05:43'),(28,'latte','2025-03-10',1,'pezzi',84,'2025-03-05 14:03:11','2025-03-05 14:03:11'),(29,'carota','2025-03-08',1,'pezzi',85,'2025-03-05 14:03:18','2025-03-05 14:03:18'),(30,'pizza','2025-03-08',1,'pezzi',86,'2025-03-05 14:03:27','2025-03-05 14:03:27'),(31,'Mostarda','2025-04-04',1,'pezzi',87,'2025-03-05 14:03:38','2025-03-05 14:03:38'),(32,'Mele','2025-03-08',1,'pezzi',88,'2025-03-05 14:03:53','2025-03-05 14:03:53'),(33,'avena','2025-04-04',1,'pezzi',89,'2025-03-05 14:04:05','2025-03-05 14:04:05'),(34,'torta compleanno','2025-03-12',1,'pezzi',90,'2025-03-05 14:04:18','2025-03-05 14:04:18'),(35,'fanta','2025-03-12',1,'pezzi',91,'2025-03-05 14:04:27','2025-03-05 14:04:27'),(36,'fagioli','2025-04-04',1,'pezzi',92,'2025-03-05 14:04:36','2025-03-05 14:04:36'),(37,'kitkat','2025-03-12',1,'pezzi',93,'2025-03-05 14:04:47','2025-03-05 14:04:47'),(38,'spigola','2025-03-07',1,'pezzi',94,'2025-03-05 14:05:02','2025-03-05 14:05:02'),(39,'marmellata pesche','2026-03-05',1,'pezzi',95,'2025-03-05 14:05:14','2025-03-05 14:05:14'),(40,'Baguette','2025-03-08',1,'pezzi',96,'2025-03-05 14:05:26','2025-03-05 14:05:26'),(41,'Burger di tofu','2025-03-08',1,'pezzi',97,'2025-03-05 14:05:52','2025-03-05 14:05:52'),(42,'Alette di pollo','2025-03-07',1,'pezzi',98,'2025-03-05 14:06:06','2025-03-05 14:06:06');
/*!40000 ALTER TABLE `prodotto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ingredients` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `generate_receipe` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipes`
--

LOCK TABLES `recipes` WRITE;
/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('AuplD4f26tTJxip5lGX7ZNJ34svhnUWYPQiCMU64',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWDN4ZFlHVnRRNUFpS1RlcXpmcFNxTFBTMFRLUWdmY3dZMFE0TEZEaSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3VzZXIvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nZXQtcmVtYWluaW5nLXJlY2lwZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1741187239),('bAE7C0zgZmrWXtNq3jTlNUbgnOrOBpkLaD2ZZxnB',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY0l4bW5idjdhZTVOa0NyZks2RWRaTDVsNzFZTzFJWXpzSlNFS0wzYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2ZyaWRnZV9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2xvZ2luIjt9fQ==',1741131852),('fJgNiJhJUQFSSrznwEQCGRjRk1cdKrAzY3N0Dt6M',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV2hVazNXOW8wV3ZHTWI1VFRmYjNPNDRSc0VndXpEN3ZGVUZ4QkZKWiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3VzZXIvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZGQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1741168394),('qlPHPGpTXxlPrtVllbL2yIe2xzb0S6w83QS0sIiL',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYmllQm5xc3I0R0lhSDk2c0kxR2RtRUZuaU51c05RQVFuUnRRMmF0WiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2ZyaWRnZV9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwIjt9fQ==',1741168020),('wFujXUVx7SBeLvpHyCUgkgIuXyDJM2C2KcwZuAm7',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUmhWUVMyTU41VjRKYWNtSUlHcjNMRjNqR0JydFQzMlpwY1cwTGZ5bSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ob21lIjt9fQ==',1741136365);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Endi','endi.dani@edu.unife.it',NULL,NULL,'$2y$12$K.LWs7iC1z39u7tWFcpL9OB70Ki3oqQ72XeFWskg1HPYsvvfp0CL6',NULL,'2025-03-04 23:20:53','2025-03-04 23:20:53','user'),(2,'claudio','claudio@bisio.com',NULL,NULL,'$2y$12$OLcKVf8ix.lzPzlw5IfEZuiFTrHUKxOEccFFOchJdWsbXeJYcLWki',NULL,'2025-03-05 08:44:26','2025-03-05 08:44:26','user');
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

-- Dump completed on 2025-03-05 16:19:46
