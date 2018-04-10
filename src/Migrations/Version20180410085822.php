<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180410085822 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hike (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, name VARCHAR(140) NOT NULL, INDEX IDX_2301D7E471F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F71F7E88B');
        $this->addSql('DROP INDEX IDX_C4E0A61F71F7E88B ON team');
        $this->addSql('ALTER TABLE team CHANGE event_id hike_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F71D4DE21 ON team (hike_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F71D4DE21');
        $this->addSql('DROP TABLE hike');
        $this->addSql('ALTER TABLE event DROP date');
        $this->addSql('DROP INDEX IDX_C4E0A61F71D4DE21 ON team');
        $this->addSql('ALTER TABLE team CHANGE hike_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F71F7E88B ON team (event_id)');
    }
}
