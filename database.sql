-- Database: pengaduan

CREATE DATABASE IF NOT EXISTS pengaduan;
USE pengaduan;

-- Table structure for table `admin`
CREATE TABLE IF NOT EXISTS `admin` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `user` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `admin` (Default Admin: admin / admin123)
-- Note: In a real app, use password hashing. For this request, I'll store plain text as requested or a simple hash if implied, but user asked for "user and password".
-- I will add a default admin for testing.
INSERT INTO `admin` (`user`, `password`, `nama`) VALUES
('admin', 'admin123', 'Administrator');

-- Table structure for table `kategori`
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` INT(11) NOT NULL AUTO_INCREMENT,
  `ket_kategori` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `kategori`
INSERT INTO `kategori` (`ket_kategori`) VALUES
('Sarana'),
('Prasarana'),
('Kebersihan'),
('Keamanan'),
('Lainnya');

-- Table structure for table `siswa`
-- Since there's no explicit registration, we might need to pre-populate or allow loose matching. 
-- However, user said "input aspirasi" has "nis". 
CREATE TABLE IF NOT EXISTS `siswa` (
  `nis` VARCHAR(20) NOT NULL,
  `kelas` VARCHAR(20) NOT NULL,
  `nama_siswa` VARCHAR(100), -- Added mainly for display if needed, though user table spec only said nis, kelas.
  PRIMARY KEY (`nis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `input_aspirasi`
CREATE TABLE IF NOT EXISTS `input_aspirasi` (
  `id_pelaporan` INT(11) NOT NULL AUTO_INCREMENT,
  `nis` VARCHAR(20) NOT NULL,
  `id_kategori` INT(11) NOT NULL,
  `lokasi` VARCHAR(100) NOT NULL,
  `keterangan` TEXT NOT NULL,
  `tgl_lapor` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pelaporan`),
  KEY `nis` (`nis`),
  KEY `id_kategori` (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `aspirasi`
-- This table tracks the status of the complaints from `input_aspirasi`.
CREATE TABLE IF NOT EXISTS `aspirasi` (
  `id_aspirasi` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pelaporan` INT(11) NOT NULL,
  `status` ENUM('menunggu','proses','selesai') NOT NULL DEFAULT 'menunggu',
  `feedback` TEXT,
  `tgl_visibilitas` DATETIME DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id_aspirasi`),
  KEY `id_pelaporan` (`id_pelaporan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Constraints for dumped tables
ALTER TABLE `input_aspirasi`
  ADD CONSTRAINT `input_aspirasi_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
  -- Note: Not adding FK for `nis` to `siswa` strictly to allow flexibility if student data isn't pre-seeded, 
  -- but generally good practice. I will add it if `siswa` table is expected to be master data.
  -- Given user instructions, I'll assume we might want to capture `nis` even if not registered yet? 
  -- Actually, let's strictly enforce if "siswa" table exists.
  
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`id_pelaporan`) REFERENCES `input_aspirasi` (`id_pelaporan`) ON DELETE CASCADE;
