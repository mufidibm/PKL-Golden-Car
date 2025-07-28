-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_bengkel
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB-log

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
-- Table structure for table `barangs`
--

DROP TABLE IF EXISTS `barangs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barangs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` enum('Sparepart','Jasa','Lainnya') NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barangs_kode_barang_unique` (`kode_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barangs`
--

LOCK TABLES `barangs` WRITE;
/*!40000 ALTER TABLE `barangs` DISABLE KEYS */;
INSERT INTO `barangs` VALUES (1,'001','Oli Mesin','Sparepart','pcs',0,20000,30000,'-','2025-07-04 06:10:16','2025-07-06 03:26:00'),(2,'002','Oli gardan','Sparepart','pcs',20,5000,7000,'-','2025-07-04 06:10:33','2025-07-04 06:10:33'),(3,'003','busi','Sparepart','pcs',9,8000,10000,'-','2025-07-04 06:10:45','2025-07-04 07:33:09'),(4,'004','Rantai','Sparepart','pcs',6,9000,11000,'-','2025-07-04 06:11:04','2025-07-06 03:26:00'),(5,'005','Jasa Mekanik','Jasa','-',9991,30000,35000,'-','2025-07-04 06:11:49','2025-07-06 03:26:00');
/*!40000 ALTER TABLE `barangs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_no_hp_unique` (`no_hp`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Feryzal','Laki-laki','08123213123','asdasdasd','2025-07-04 06:09:23','2025-07-04 06:09:23'),(7,'Yosep Tatang','Laki-laki','99999999','Jl. Jakarta, Jakarta','2025-07-06 03:23:20','2025-07-06 03:23:20');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departemens`
--

DROP TABLE IF EXISTS `departemens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departemens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_departemen` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departemens_nama_departemen_unique` (`nama_departemen`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departemens`
--

LOCK TABLES `departemens` WRITE;
/*!40000 ALTER TABLE `departemens` DISABLE KEYS */;
INSERT INTO `departemens` VALUES (1,'Mekanik','Mekanik Handal','2025-07-04 06:07:05','2025-07-04 06:07:05');
/*!40000 ALTER TABLE `departemens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `jabatans`
--

DROP TABLE IF EXISTS `jabatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jabatans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jabatans_nama_jabatan_unique` (`nama_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jabatans`
--

LOCK TABLES `jabatans` WRITE;
/*!40000 ALTER TABLE `jabatans` DISABLE KEYS */;
INSERT INTO `jabatans` VALUES (1,'Kepala Mekanik','Mekanik Handal','2025-07-04 06:08:19','2025-07-04 06:08:19');
/*!40000 ALTER TABLE `jabatans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kendaraans`
--

DROP TABLE IF EXISTS `kendaraans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kendaraans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `no_polisi` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `merek` varchar(255) NOT NULL,
  `tahun` year(4) NOT NULL,
  `warna` varchar(255) NOT NULL,
  `jenis_kendaraan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kendaraans_no_polisi_unique` (`no_polisi`),
  KEY `kendaraans_customer_id_foreign` (`customer_id`),
  CONSTRAINT `kendaraans_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kendaraans`
--

LOCK TABLES `kendaraans` WRITE;
/*!40000 ALTER TABLE `kendaraans` DISABLE KEYS */;
INSERT INTO `kendaraans` VALUES (1,1,'W 1234 ABC','Innova Venturrer','Toyota',2024,'Hitam','Mobil','2025-07-04 06:09:44','2025-07-04 06:09:44'),(4,7,'B 9999 BA','Innova Venturrer','Toyota',2025,'Hitam','Mobil','2025-07-06 03:23:49','2025-07-06 03:23:49');
/*!40000 ALTER TABLE `kendaraans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (3,'App\\Models\\Setting',3,'6f574f1e-5ce5-4dee-b06b-c45852968965','logo','pngtree-workshop-mechanic-logo-design-png-image_18710882','pngtree-workshop-mechanic-logo-design-png-image_18710882.png','image/png','public','public',1253972,'[]','[]','[]','[]',1,'2025-07-05 14:47:07','2025-07-05 14:47:07');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metode_pembayarans`
--

DROP TABLE IF EXISTS `metode_pembayarans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metode_pembayarans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_metode` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metode_pembayarans`
--

LOCK TABLES `metode_pembayarans` WRITE;
/*!40000 ALTER TABLE `metode_pembayarans` DISABLE KEYS */;
INSERT INTO `metode_pembayarans` VALUES (1,'Cash','Pembayaran Cash','Aktif','2025-07-04 07:32:03','2025-07-04 07:32:03'),(2,'QRIS','Pembayaran Qris','Aktif','2025-07-04 07:32:15','2025-07-04 07:32:15');
/*!40000 ALTER TABLE `metode_pembayarans` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_07_03_140527_create_customers_table',1),(6,'2025_07_03_143251_create_kendaraans_table',1),(7,'2025_07_03_164609_create_barangs_table',1),(8,'2025_07_03_165441_create_stok_opnames_table',1),(9,'2025_07_03_172336_create_jabatans_table',1),(10,'2025_07_03_172830_create_departemens_table',1),(11,'2025_07_03_173743_create_pegawais_table',1),(12,'2025_07_03_174812_create_permission_tables',1),(13,'2025_07_03_174931_add_pegawai_id_to_users_table',1),(14,'2025_07_04_020504_create_transaksi_masuk_table',1),(15,'2025_07_04_043548_create_pengerjaan_servis_table',1),(16,'2025_07_04_062213_create_pengerjaan_spareparts_table',1),(17,'2025_07_06_000000_create_transaksi_masuk_items_table',1),(18,'2025_07_04_142830_create_metode_pembayarans_table',2),(19,'2025_07_04_160441_create_pembayaran_details_table',3),(20,'2025_07_05_143213_create_settings_table',4),(21,'2025_07_04_160419_create_pembayarans_table',5),(22,'2025_07_05_154219_create_media_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
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
INSERT INTO `model_has_permissions` VALUES (2,'App\\Models\\User',2),(3,'App\\Models\\User',2),(4,'App\\Models\\User',2),(5,'App\\Models\\User',2);
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
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
INSERT INTO `model_has_roles` VALUES (2,'App\\Models\\User',2);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
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
-- Table structure for table `pegawais`
--

DROP TABLE IF EXISTS `pegawais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pegawais` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status_kepegawaian` enum('aktif','tidak') NOT NULL DEFAULT 'aktif',
  `departemen_id` bigint(20) unsigned NOT NULL,
  `jabatan_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pegawais_nip_unique` (`nip`),
  UNIQUE KEY `pegawais_email_unique` (`email`),
  KEY `pegawais_departemen_id_foreign` (`departemen_id`),
  KEY `pegawais_jabatan_id_foreign` (`jabatan_id`),
  CONSTRAINT `pegawais_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `departemens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pegawais_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pegawais`
--

LOCK TABLES `pegawais` WRITE;
/*!40000 ALTER TABLE `pegawais` DISABLE KEYS */;
INSERT INTO `pegawais` VALUES (1,'001','Reno','1982-10-10','L','2024-10-10','asdasdasdasd','123123123123','reno@app.com','aktif',1,1,'2025-07-04 06:08:40','2025-07-04 06:08:40');
/*!40000 ALTER TABLE `pegawais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran_details`
--

DROP TABLE IF EXISTS `pembayaran_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembayaran_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pembayaran_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `nama_item` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_details_pembayaran_id_foreign` (`pembayaran_id`),
  CONSTRAINT `pembayaran_details_pembayaran_id_foreign` FOREIGN KEY (`pembayaran_id`) REFERENCES `pembayarans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran_details`
--

LOCK TABLES `pembayaran_details` WRITE;
/*!40000 ALTER TABLE `pembayaran_details` DISABLE KEYS */;
INSERT INTO `pembayaran_details` VALUES (36,21,4,'Rantai',1,11000,11000,'2025-07-05 10:47:00','2025-07-05 10:47:00'),(37,21,5,'Jasa Mekanik',2,35000,70000,'2025-07-05 10:47:00','2025-07-05 10:47:00'),(41,23,1,'Oli Mesin',4,30000,120000,'2025-07-06 03:27:17','2025-07-06 03:27:17'),(42,23,5,'Jasa Mekanik',1,35000,35000,'2025-07-06 03:27:17','2025-07-06 03:27:17'),(43,23,4,'Rantai',1,11000,11000,'2025-07-06 03:27:17','2025-07-06 03:27:17');
/*!40000 ALTER TABLE `pembayaran_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayarans`
--

DROP TABLE IF EXISTS `pembayarans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembayarans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_transaksi_masuk` bigint(20) unsigned NOT NULL,
  `metode_pembayaran_id` bigint(20) unsigned NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `dibayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL DEFAULT 0,
  `kasir_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayarans_id_transaksi_masuk_foreign` (`id_transaksi_masuk`),
  CONSTRAINT `pembayarans_id_transaksi_masuk_foreign` FOREIGN KEY (`id_transaksi_masuk`) REFERENCES `transaksi_masuk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayarans`
--

LOCK TABLES `pembayarans` WRITE;
/*!40000 ALTER TABLE `pembayarans` DISABLE KEYS */;
INSERT INTO `pembayarans` VALUES (21,2,1,81000,100000,0,NULL,'2025-07-05 10:47:00','2025-07-05 10:47:00'),(23,5,1,166000,200000,0,NULL,'2025-07-06 03:27:17','2025-07-06 03:27:17');
/*!40000 ALTER TABLE `pembayarans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengerjaan_servis`
--

DROP TABLE IF EXISTS `pengerjaan_servis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pengerjaan_servis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_masuk_id` bigint(20) unsigned NOT NULL,
  `mekanik_id` bigint(20) unsigned NOT NULL,
  `status` enum('Waiting','Sedang Dikerjakan','Menunggu Sparepart','Pemeriksaan Akhir','Selesai') NOT NULL,
  `catatan` text DEFAULT NULL,
  `mulai` timestamp NULL DEFAULT NULL,
  `selesai` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengerjaan_servis_transaksi_masuk_id_foreign` (`transaksi_masuk_id`),
  KEY `pengerjaan_servis_mekanik_id_foreign` (`mekanik_id`),
  CONSTRAINT `pengerjaan_servis_mekanik_id_foreign` FOREIGN KEY (`mekanik_id`) REFERENCES `pegawais` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengerjaan_servis_transaksi_masuk_id_foreign` FOREIGN KEY (`transaksi_masuk_id`) REFERENCES `transaksi_masuk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengerjaan_servis`
--

LOCK TABLES `pengerjaan_servis` WRITE;
/*!40000 ALTER TABLE `pengerjaan_servis` DISABLE KEYS */;
INSERT INTO `pengerjaan_servis` VALUES (5,2,1,'Selesai','Proses','2025-07-05 07:56:09','2025-07-05 10:56:12','2025-07-05 00:56:17','2025-07-05 04:19:50'),(7,5,1,'Selesai','Proses Pengecekan oleh mekanik handal','2025-07-06 10:24:37','2025-07-06 12:24:41','2025-07-06 03:24:55','2025-07-06 03:26:28');
/*!40000 ALTER TABLE `pengerjaan_servis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengerjaan_spareparts`
--

DROP TABLE IF EXISTS `pengerjaan_spareparts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pengerjaan_spareparts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pengerjaan_servis_id` bigint(20) unsigned NOT NULL,
  `barang_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengerjaan_spareparts_pengerjaan_servis_id_foreign` (`pengerjaan_servis_id`),
  KEY `pengerjaan_spareparts_barang_id_foreign` (`barang_id`),
  CONSTRAINT `pengerjaan_spareparts_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengerjaan_spareparts_pengerjaan_servis_id_foreign` FOREIGN KEY (`pengerjaan_servis_id`) REFERENCES `pengerjaan_servis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengerjaan_spareparts`
--

LOCK TABLES `pengerjaan_spareparts` WRITE;
/*!40000 ALTER TABLE `pengerjaan_spareparts` DISABLE KEYS */;
INSERT INTO `pengerjaan_spareparts` VALUES (4,5,4,1,11000.00,11000.00,'2025-07-05 00:56:33','2025-07-05 00:56:33'),(5,5,5,2,35000.00,70000.00,'2025-07-05 00:56:45','2025-07-05 00:56:45'),(9,7,1,4,30000.00,120000.00,'2025-07-06 03:26:00','2025-07-06 03:26:00'),(10,7,5,1,35000.00,35000.00,'2025-07-06 03:26:00','2025-07-06 03:26:00'),(11,7,4,1,11000.00,11000.00,'2025-07-06 03:26:00','2025-07-06 03:26:00');
/*!40000 ALTER TABLE `pengerjaan_spareparts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'dashboard','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(2,'laporan pembayaran','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(3,'laporan pendapatan','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(4,'transaksi','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(5,'master pelanggan','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(6,'manajemen inventory','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(7,'kepegawaian','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(8,'master data keuangan','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(9,'service manajemen','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(10,'manajemen user','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(11,'lainnya','web','2025-07-05 14:02:43','2025-07-05 14:02:43');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
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
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
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
INSERT INTO `role_has_permissions` VALUES (1,1),(1,4),(2,1),(2,2),(2,4),(3,1),(3,2),(3,4),(4,1),(4,2),(5,1),(5,2),(6,1),(7,1),(8,1),(8,4),(9,1),(9,3),(10,1),(11,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','web','2025-07-05 13:55:14','2025-07-05 13:55:20'),(2,'kasir','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(3,'mekanik','web','2025-07-05 14:02:43','2025-07-05 14:02:43'),(4,'owner','web','2025-07-05 14:02:43','2025-07-05 14:02:43');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_bengkel` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (3,'Golden Car','Jl. Raya Jati Mekar No.24, Bekasi','0813 8390 2292','admin@golden-car.co.id','logos/KbaZO8zVFwWkvTb6X9pRefMc8XoFlY-metacG5ndHJlZS1zZXR0aW5nLWFuZC13b3Jrc2hvcC1sb2dvLXZlY3Rvci1wbmctaW1hZ2VfODkyOTMyNi5wbmc=-.png','2025-07-05 08:13:39','2025-07-05 14:29:40');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stok_opnames`
--

DROP TABLE IF EXISTS `stok_opnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stok_opnames` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barang_id` bigint(20) unsigned NOT NULL,
  `stok_lama` int(11) NOT NULL,
  `stok_baru` int(11) NOT NULL,
  `selisih` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal_opname` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stok_opnames_barang_id_foreign` (`barang_id`),
  CONSTRAINT `stok_opnames_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stok_opnames`
--

LOCK TABLES `stok_opnames` WRITE;
/*!40000 ALTER TABLE `stok_opnames` DISABLE KEYS */;
/*!40000 ALTER TABLE `stok_opnames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_masuk`
--

DROP TABLE IF EXISTS `transaksi_masuk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi_masuk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kendaraan_id` bigint(20) unsigned NOT NULL,
  `status` enum('menunggu','sedang dikerjakan','menunggu sparepart','pemeriksaan akhir','selesai') NOT NULL DEFAULT 'menunggu',
  `waktu_masuk` timestamp NOT NULL DEFAULT '2025-07-04 05:55:29',
  `keluhan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_masuk_kendaraan_id_foreign` (`kendaraan_id`),
  CONSTRAINT `transaksi_masuk_kendaraan_id_foreign` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_masuk`
--

LOCK TABLES `transaksi_masuk` WRITE;
/*!40000 ALTER TABLE `transaksi_masuk` DISABLE KEYS */;
INSERT INTO `transaksi_masuk` VALUES (2,1,'selesai','2025-07-04 17:00:00','Macet','2025-07-05 00:55:50','2025-07-05 04:19:50'),(5,4,'selesai','2025-07-05 17:00:00','Upgrade Biar tambah kenceng','2025-07-06 03:24:20','2025-07-06 03:26:28');
/*!40000 ALTER TABLE `transaksi_masuk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_masuk_items`
--

DROP TABLE IF EXISTS `transaksi_masuk_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi_masuk_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_masuk_id` bigint(20) unsigned NOT NULL,
  `barang_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_masuk_items_transaksi_masuk_id_foreign` (`transaksi_masuk_id`),
  KEY `transaksi_masuk_items_barang_id_foreign` (`barang_id`),
  CONSTRAINT `transaksi_masuk_items_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_masuk_items_transaksi_masuk_id_foreign` FOREIGN KEY (`transaksi_masuk_id`) REFERENCES `transaksi_masuk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_masuk_items`
--

LOCK TABLES `transaksi_masuk_items` WRITE;
/*!40000 ALTER TABLE `transaksi_masuk_items` DISABLE KEYS */;
INSERT INTO `transaksi_masuk_items` VALUES (1,2,4,1,11000.00,11000.00,'2025-07-05 07:22:58','2025-07-05 07:22:58'),(2,2,5,2,35000.00,70000.00,'2025-07-05 07:22:58','2025-07-05 07:22:58'),(3,2,4,1,11000.00,11000.00,'2025-07-05 10:47:00','2025-07-05 10:47:00'),(4,2,5,2,35000.00,70000.00,'2025-07-05 10:47:00','2025-07-05 10:47:00'),(8,5,1,4,30000.00,120000.00,'2025-07-06 03:27:17','2025-07-06 03:27:17'),(9,5,5,1,35000.00,35000.00,'2025-07-06 03:27:17','2025-07-06 03:27:17'),(10,5,4,1,11000.00,11000.00,'2025-07-06 03:27:17','2025-07-06 03:27:17');
/*!40000 ALTER TABLE `transaksi_masuk_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pegawai_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_pegawai_id_foreign` (`pegawai_id`),
  CONSTRAINT `users_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawais` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'SuperAdmin','admin@app.com',NULL,'$2y$10$JrT67/25n.V8uU/upWPIa.wUX9k9ZHM6urBtTMuPq.EBeOjdSw0z.','VqNmSdbb0jFgxa5Q9TlrexToaJNXx4v5rI493CTypMZnC74kPJ6zABqb0wuc','2025-07-04 05:55:49','2025-07-04 05:55:49',NULL),(2,'Reno','reno@app.com',NULL,'$2y$10$KXmsfqiZiubLOdScl7oJpuDcXrKKbubc23oG4svPQnx1oe8sglQSm',NULL,'2025-07-05 14:03:38','2025-07-05 14:26:43',1);
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

-- Dump completed on 2025-07-11 10:49:50
