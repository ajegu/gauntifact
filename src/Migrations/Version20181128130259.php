<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181128130259 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck_card (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, deck_id INT NOT NULL, count INT NOT NULL, turn INT NOT NULL, INDEX IDX_2AF3DCED4ACC9A20 (card_id), INDEX IDX_2AF3DCED111948DC (deck_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('ALTER TABLE gauntlet ADD deck_id INT NOT NULL');
        $this->addSql('ALTER TABLE gauntlet ADD CONSTRAINT FK_5524B8C111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('CREATE INDEX IDX_5524B8C111948DC ON gauntlet (deck_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED111948DC');
        $this->addSql('ALTER TABLE gauntlet DROP FOREIGN KEY FK_5524B8C111948DC');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE deck_card');
        $this->addSql('DROP INDEX IDX_5524B8C111948DC ON gauntlet');
        $this->addSql('ALTER TABLE gauntlet DROP deck_id');
    }
}
