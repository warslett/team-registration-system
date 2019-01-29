<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129222806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F71D4DE21');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE walker DROP FOREIGN KEY FK_904421AF296CD8AE');
        $this->addSql('ALTER TABLE walker ADD CONSTRAINT FK_904421AF296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hike DROP FOREIGN KEY FK_2301D7E471F7E88B');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hike DROP FOREIGN KEY FK_2301D7E471F7E88B');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F71D4DE21');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id)');
        $this->addSql('ALTER TABLE walker DROP FOREIGN KEY FK_904421AF296CD8AE');
        $this->addSql('ALTER TABLE walker ADD CONSTRAINT FK_904421AF296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }
}
