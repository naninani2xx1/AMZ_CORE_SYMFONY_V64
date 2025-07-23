<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250706025039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_article (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, post_id INT DEFAULT NULL, INDEX IDX_102DBB55F675F31B (author_id), UNIQUE INDEX UNIQ_102DBB554B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_block_content (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, category_id INT DEFAULT NULL, sort_order INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, sub_title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, config LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, status LONGTEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, image_icon LONGTEXT DEFAULT NULL, image LONGTEXT DEFAULT NULL, image_mobile LONGTEXT DEFAULT NULL, background LONGTEXT DEFAULT NULL, mobile_background LONGTEXT DEFAULT NULL, type LONGTEXT DEFAULT NULL, text_icon LONGTEXT DEFAULT NULL, url LONGTEXT DEFAULT NULL, location LONGTEXT DEFAULT NULL, video_url LONGTEXT DEFAULT NULL, kind VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C432E08989D9B62 (slug), INDEX IDX_C432E084B89032C (post_id), INDEX IDX_C432E0812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_category (id INT AUTO_INCREMENT NOT NULL, parent INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, thumbnail LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, sort_order INT DEFAULT NULL, level VARCHAR(50) DEFAULT NULL, custom_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B98E6F62989D9B62 (slug), INDEX IDX_B98E6F623D8E604F (parent), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_page (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, seo_url VARCHAR(255) DEFAULT NULL, css VARCHAR(255) DEFAULT NULL, custom_css LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_26EF75154B89032C (post_id), INDEX IDX_26EF7515727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_picture (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, image LONGTEXT NOT NULL, image_mobile LONGTEXT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, sub_title VARCHAR(255) DEFAULT NULL, link LONGTEXT DEFAULT NULL, sort_order INT DEFAULT NULL, INDEX IDX_4CCFABA4E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_post (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, gallery_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, sub_title VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, thumbnail LONGTEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, sort_order INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, is_hot INT DEFAULT NULL, is_new INT DEFAULT NULL, post_type INT DEFAULT NULL, published VARCHAR(255) DEFAULT NULL, tag LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', config LONGTEXT DEFAULT NULL, INDEX IDX_686FAFB812469DE2 (category_id), UNIQUE INDEX UNIQ_686FAFB84E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_social_sharing (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, google_title VARCHAR(255) DEFAULT NULL, google_description LONGTEXT DEFAULT NULL, google_tag LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', facebook_title VARCHAR(255) DEFAULT NULL, facebook_description LONGTEXT DEFAULT NULL, facebook_thumbnail LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_DAA6D07A4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(100) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, salt LONGTEXT DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_BF76157CF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_article ADD CONSTRAINT FK_102DBB55F675F31B FOREIGN KEY (author_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_article ADD CONSTRAINT FK_102DBB554B89032C FOREIGN KEY (post_id) REFERENCES core_post (id)');
        $this->addSql('ALTER TABLE core_block_content ADD CONSTRAINT FK_C432E084B89032C FOREIGN KEY (post_id) REFERENCES core_post (id)');
        $this->addSql('ALTER TABLE core_block_content ADD CONSTRAINT FK_C432E0812469DE2 FOREIGN KEY (category_id) REFERENCES core_category (id)');
        $this->addSql('ALTER TABLE core_category ADD CONSTRAINT FK_B98E6F623D8E604F FOREIGN KEY (parent) REFERENCES core_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_page ADD CONSTRAINT FK_26EF75154B89032C FOREIGN KEY (post_id) REFERENCES core_post (id)');
        $this->addSql('ALTER TABLE core_page ADD CONSTRAINT FK_26EF7515727ACA70 FOREIGN KEY (parent_id) REFERENCES core_page (id)');
        $this->addSql('ALTER TABLE core_picture ADD CONSTRAINT FK_4CCFABA4E7AF8F FOREIGN KEY (gallery_id) REFERENCES core_gallery (id)');
        $this->addSql('ALTER TABLE core_post ADD CONSTRAINT FK_686FAFB812469DE2 FOREIGN KEY (category_id) REFERENCES core_category (id)');
        $this->addSql('ALTER TABLE core_post ADD CONSTRAINT FK_686FAFB84E7AF8F FOREIGN KEY (gallery_id) REFERENCES core_gallery (id)');
        $this->addSql('ALTER TABLE core_social_sharing ADD CONSTRAINT FK_DAA6D07A4B89032C FOREIGN KEY (post_id) REFERENCES core_post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_article DROP FOREIGN KEY FK_102DBB55F675F31B');
        $this->addSql('ALTER TABLE core_article DROP FOREIGN KEY FK_102DBB554B89032C');
        $this->addSql('ALTER TABLE core_block_content DROP FOREIGN KEY FK_C432E084B89032C');
        $this->addSql('ALTER TABLE core_block_content DROP FOREIGN KEY FK_C432E0812469DE2');
        $this->addSql('ALTER TABLE core_category DROP FOREIGN KEY FK_B98E6F623D8E604F');
        $this->addSql('ALTER TABLE core_page DROP FOREIGN KEY FK_26EF75154B89032C');
        $this->addSql('ALTER TABLE core_page DROP FOREIGN KEY FK_26EF7515727ACA70');
        $this->addSql('ALTER TABLE core_picture DROP FOREIGN KEY FK_4CCFABA4E7AF8F');
        $this->addSql('ALTER TABLE core_post DROP FOREIGN KEY FK_686FAFB812469DE2');
        $this->addSql('ALTER TABLE core_post DROP FOREIGN KEY FK_686FAFB84E7AF8F');
        $this->addSql('ALTER TABLE core_social_sharing DROP FOREIGN KEY FK_DAA6D07A4B89032C');
        $this->addSql('DROP TABLE core_article');
        $this->addSql('DROP TABLE core_block_content');
        $this->addSql('DROP TABLE core_category');
        $this->addSql('DROP TABLE core_gallery');
        $this->addSql('DROP TABLE core_page');
        $this->addSql('DROP TABLE core_picture');
        $this->addSql('DROP TABLE core_post');
        $this->addSql('DROP TABLE core_social_sharing');
        $this->addSql('DROP TABLE core_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
