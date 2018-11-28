<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181128185335 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, rarity_id INT DEFAULT NULL, sub_type_id INT DEFAULT NULL, card_set_id INT NOT NULL, game_id INT NOT NULL, name VARCHAR(100) DEFAULT NULL, text LONGTEXT DEFAULT NULL, mini_image LONGTEXT DEFAULT NULL, large_image LONGTEXT DEFAULT NULL, in_game_image LONGTEXT DEFAULT NULL, illustrator VARCHAR(50) DEFAULT NULL, attack INT NOT NULL, hit_points INT NOT NULL, mana_cost INT NOT NULL, gold_cost INT NOT NULL, armor INT NOT NULL, blue TINYINT(1) NOT NULL, black TINYINT(1) NOT NULL, green TINYINT(1) NOT NULL, red TINYINT(1) NOT NULL, charges INT NOT NULL, cross_lane TINYINT(1) NOT NULL, quick TINYINT(1) NOT NULL, INDEX IDX_161498D3C54C8C93 (type_id), INDEX IDX_161498D3F3747573 (rarity_id), INDEX IDX_161498D3BA94D067 (sub_type_id), INDEX IDX_161498D362C45E6C (card_set_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_card_reference (card_id INT NOT NULL, card_reference_id INT NOT NULL, INDEX IDX_96B2C0D04ACC9A20 (card_id), INDEX IDX_96B2C0D067870AC (card_reference_id), PRIMARY KEY(card_id, card_reference_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_set (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, opposing_deck_id INT NOT NULL, gauntlet_id INT DEFAULT NULL, status VARCHAR(20) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_232B318C11B292B5 (opposing_deck_id), INDEX IDX_232B318C188681C7 (gauntlet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gauntlet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type_id INT NOT NULL, deck_id INT NOT NULL, status VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, number INT NOT NULL, INDEX IDX_5524B8CA76ED395 (user_id), INDEX IDX_5524B8CC54C8C93 (type_id), INDEX IDX_5524B8C111948DC (deck_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gauntlet_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_reference (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, card_id INT NOT NULL, count INT NOT NULL, INDEX IDX_DAABAA47C54C8C93 (type_id), INDEX IDX_DAABAA474ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, code LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck_card (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, deck_id INT NOT NULL, count INT NOT NULL, turn INT DEFAULT NULL, INDEX IDX_2AF3DCED4ACC9A20 (card_id), INDEX IDX_2AF3DCED111948DC (deck_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_rarity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_reference_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_sub_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3C54C8C93 FOREIGN KEY (type_id) REFERENCES card_type (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3F3747573 FOREIGN KEY (rarity_id) REFERENCES card_rarity (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3BA94D067 FOREIGN KEY (sub_type_id) REFERENCES card_sub_type (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D362C45E6C FOREIGN KEY (card_set_id) REFERENCES card_set (id)');
        $this->addSql('ALTER TABLE card_card_reference ADD CONSTRAINT FK_96B2C0D04ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_card_reference ADD CONSTRAINT FK_96B2C0D067870AC FOREIGN KEY (card_reference_id) REFERENCES card_reference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C11B292B5 FOREIGN KEY (opposing_deck_id) REFERENCES deck (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C188681C7 FOREIGN KEY (gauntlet_id) REFERENCES gauntlet (id)');
        $this->addSql('ALTER TABLE gauntlet ADD CONSTRAINT FK_5524B8CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gauntlet ADD CONSTRAINT FK_5524B8CC54C8C93 FOREIGN KEY (type_id) REFERENCES gauntlet_type (id)');
        $this->addSql('ALTER TABLE gauntlet ADD CONSTRAINT FK_5524B8C111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('ALTER TABLE card_reference ADD CONSTRAINT FK_DAABAA47C54C8C93 FOREIGN KEY (type_id) REFERENCES card_reference_type (id)');
        $this->addSql('ALTER TABLE card_reference ADD CONSTRAINT FK_DAABAA474ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_card_reference DROP FOREIGN KEY FK_96B2C0D04ACC9A20');
        $this->addSql('ALTER TABLE card_reference DROP FOREIGN KEY FK_DAABAA474ACC9A20');
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED4ACC9A20');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D362C45E6C');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3C54C8C93');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C188681C7');
        $this->addSql('ALTER TABLE gauntlet DROP FOREIGN KEY FK_5524B8CC54C8C93');
        $this->addSql('ALTER TABLE gauntlet DROP FOREIGN KEY FK_5524B8CA76ED395');
        $this->addSql('ALTER TABLE card_card_reference DROP FOREIGN KEY FK_96B2C0D067870AC');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C11B292B5');
        $this->addSql('ALTER TABLE gauntlet DROP FOREIGN KEY FK_5524B8C111948DC');
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED111948DC');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3F3747573');
        $this->addSql('ALTER TABLE card_reference DROP FOREIGN KEY FK_DAABAA47C54C8C93');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3BA94D067');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_card_reference');
        $this->addSql('DROP TABLE card_set');
        $this->addSql('DROP TABLE card_type');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE gauntlet');
        $this->addSql('DROP TABLE gauntlet_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE card_reference');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE deck_card');
        $this->addSql('DROP TABLE card_rarity');
        $this->addSql('DROP TABLE card_reference_type');
        $this->addSql('DROP TABLE card_sub_type');
        $this->addSql('DROP TABLE ext_translations');
    }
}
