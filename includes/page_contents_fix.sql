-- Fix page_contents table for bilingual content support
-- Adds missing columns for English and French content

-- Add bilingual content columns and updated_by tracking
ALTER TABLE page_contents
ADD COLUMN IF NOT EXISTS content_en TEXT AFTER content_type,
ADD COLUMN IF NOT EXISTS content_fr TEXT AFTER content_en,
ADD COLUMN IF NOT EXISTS updated_by INT AFTER is_active;

-- Add foreign key if it doesn't exist
-- Note: Run this separately if the above ALTER fails
-- ALTER TABLE page_contents ADD FOREIGN KEY (updated_by) REFERENCES admins(id) ON DELETE SET NULL;

-- Insert sample content for About page
INSERT INTO page_contents (page_name, section_key, content_type, content_en, content_fr, display_order) VALUES
('about', 'page_title', 'text', 'About Bihak Center', 'À propos de Bihak Center', 1),
('about', 'hero_subtitle', 'text', 'Empowering refugees and displaced persons in Rwanda', 'Autonomiser les réfugiés et les personnes déplacées au Rwanda', 2),
('about', 'vision_title', 'text', 'Our Vision', 'Notre Vision', 3),
('about', 'vision_content', 'paragraph', 'To create a world where every refugee and displaced person has access to opportunities for growth, dignity, and self-sufficiency.', 'Créer un monde où chaque réfugié et personne déplacée a accès à des opportunités de croissance, de dignité et d''autosuffisance.', 4),
('about', 'mission_title', 'text', 'Our Mission', 'Notre Mission', 5),
('about', 'mission_content', 'paragraph', 'To provide comprehensive support services including education, skills training, and community integration programs for refugees and displaced persons in Rwanda.', 'Fournir des services de soutien complets, y compris l''éducation, la formation professionnelle et des programmes d''intégration communautaire pour les réfugiés et les personnes déplacées au Rwanda.', 6)
ON DUPLICATE KEY UPDATE content_en=VALUES(content_en), content_fr=VALUES(content_fr);

-- Insert sample content for Home page
INSERT INTO page_contents (page_name, section_key, content_type, content_en, content_fr, display_order) VALUES
('home', 'hero_title', 'text', 'Welcome to Bihak Center', 'Bienvenue au Bihak Center', 1),
('home', 'hero_subtitle', 'text', 'Empowering Lives, Building Futures', 'Autonomiser les Vies, Construire l''Avenir', 2),
('home', 'about_heading', 'text', 'Who We Are', 'Qui Sommes-Nous', 3),
('home', 'about_text', 'paragraph', 'Bihak Center is dedicated to supporting refugees and displaced persons in Rwanda through education, skills training, and community integration programs.', 'Le Bihak Center se consacre à soutenir les réfugiés et les personnes déplacées au Rwanda à travers l''éducation, la formation professionnelle et des programmes d''intégration communautaire.', 4),
('home', 'impact_heading', 'text', 'Our Impact', 'Notre Impact', 5),
('home', 'cta_heading', 'text', 'Get Involved', 'Impliquez-vous', 6),
('home', 'cta_text', 'paragraph', 'Join us in making a difference in the lives of refugees and displaced persons. Your support can change lives.', 'Joignez-vous à nous pour faire une différence dans la vie des réfugiés et des personnes déplacées. Votre soutien peut changer des vies.', 7)
ON DUPLICATE KEY UPDATE content_en=VALUES(content_en), content_fr=VALUES(content_fr);

-- Insert sample content for Contact and Work pages
INSERT INTO page_contents (page_name, section_key, content_type, content_en, content_fr, display_order) VALUES
('contact', 'page_title', 'text', 'Contact Us', 'Contactez-Nous', 1),
('contact', 'page_subtitle', 'text', 'Get in touch with our team', 'Contactez notre équipe', 2),
('contact', 'address_label', 'text', 'Our Address', 'Notre Adresse', 3),
('contact', 'email_label', 'text', 'Email', 'Email', 4),
('contact', 'phone_label', 'text', 'Phone', 'Téléphone', 5),
('work', 'page_title', 'text', 'Success Stories', 'Histoires de Réussite', 6),
('work', 'page_subtitle', 'text', 'Inspiring journeys of resilience and hope', 'Parcours inspirants de résilience et d''espoir', 7)
ON DUPLICATE KEY UPDATE content_en=VALUES(content_en), content_fr=VALUES(content_fr);

-- Verify the changes
SELECT page_name, COUNT(*) as content_count
FROM page_contents
WHERE is_active=TRUE
GROUP BY page_name;
