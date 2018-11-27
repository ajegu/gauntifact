<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181127122019 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, rarity_id INT DEFAULT NULL, sub_type_id INT DEFAULT NULL, card_set_id INT NOT NULL, game_id INT NOT NULL, name VARCHAR(100) NOT NULL, text LONGTEXT DEFAULT NULL, mini_image LONGTEXT DEFAULT NULL, large_image LONGTEXT DEFAULT NULL, in_game_image LONGTEXT DEFAULT NULL, illustrator VARCHAR(50) DEFAULT NULL, attack INT NOT NULL, hit_points INT NOT NULL, mana_cost INT NOT NULL, gold_cost INT NOT NULL, armor INT NOT NULL, blue TINYINT(1) NOT NULL, black TINYINT(1) NOT NULL, green TINYINT(1) NOT NULL, red TINYINT(1) NOT NULL, charges INT NOT NULL, cross_lane TINYINT(1) NOT NULL, quick TINYINT(1) NOT NULL, INDEX IDX_161498D3C54C8C93 (type_id), INDEX IDX_161498D3F3747573 (rarity_id), INDEX IDX_161498D3BA94D067 (sub_type_id), INDEX IDX_161498D362C45E6C (card_set_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_card_reference (card_id INT NOT NULL, card_reference_id INT NOT NULL, INDEX IDX_96B2C0D04ACC9A20 (card_id), INDEX IDX_96B2C0D067870AC (card_reference_id), PRIMARY KEY(card_id, card_reference_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_reference (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, card_id INT NOT NULL, count INT NOT NULL, INDEX IDX_DAABAA47C54C8C93 (type_id), INDEX IDX_DAABAA474ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3C54C8C93 FOREIGN KEY (type_id) REFERENCES card_type (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3F3747573 FOREIGN KEY (rarity_id) REFERENCES card_rarity (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3BA94D067 FOREIGN KEY (sub_type_id) REFERENCES card_sub_type (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D362C45E6C FOREIGN KEY (card_set_id) REFERENCES card_set (id)');
        $this->addSql('ALTER TABLE card_card_reference ADD CONSTRAINT FK_96B2C0D04ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_card_reference ADD CONSTRAINT FK_96B2C0D067870AC FOREIGN KEY (card_reference_id) REFERENCES card_reference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_reference ADD CONSTRAINT FK_DAABAA47C54C8C93 FOREIGN KEY (type_id) REFERENCES card_reference_type (id)');
        $this->addSql('ALTER TABLE card_reference ADD CONSTRAINT FK_DAABAA474ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_card_reference DROP FOREIGN KEY FK_96B2C0D04ACC9A20');
        $this->addSql('ALTER TABLE card_reference DROP FOREIGN KEY FK_DAABAA474ACC9A20');
        $this->addSql('ALTER TABLE card_card_reference DROP FOREIGN KEY FK_96B2C0D067870AC');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_card_reference');
        $this->addSql('DROP TABLE card_reference');
    }
}
