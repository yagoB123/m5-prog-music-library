DROP TABLE IF EXISTS singles;
DROP TABLE IF EXISTS artists;
DROP TABLE IF EXISTS genres;

CREATE TABLE artists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  image VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE genres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE singles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  duration_seconds INT NOT NULL,
  release_year INT NOT NULL,
  artist_id INT NOT NULL,
  genre_id INT NOT NULL,
  track_image VARCHAR(255) NOT NULL,
  CONSTRAINT fk_singles_artist FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE CASCADE,
  CONSTRAINT fk_singles_genre FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO artists (name, slug, image) VALUES
('The Weeknd', 'the-weeknd', ''),
('Dua Lipa', 'dua-lipa', ''),
('Kendrick Lamar', 'kendrick-lamar', '');

INSERT INTO genres (name, slug) VALUES
('Pop', 'pop'),
('R&B', 'rnb'),
('Hip-Hop', 'hip-hop');

INSERT INTO singles (title, slug, duration_seconds, release_year, artist_id, genre_id, track_image) VALUES
('Blinding Lights', 'blinding-lights', 200, 2019, 1, 1, ''),
('Save Your Tears', 'save-your-tears', 215, 2020, 1, 2, ''),
('Levitating', 'levitating', 203, 2020, 2, 1, ''),
('HUMBLE.', 'humble', 177, 2017, 3, 3, ''),
('Starboy', 'starboy', 230, 2016, 1, 1, '');
