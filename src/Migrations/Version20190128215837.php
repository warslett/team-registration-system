<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190128215837 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(254) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_usergroups (user_id INT NOT NULL, role VARCHAR(100) NOT NULL, INDEX IDX_4FFC8120A76ED395 (user_id), INDEX IDX_4FFC812057698A6A (role), PRIMARY KEY(user_id, role)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, hike_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, is_dropped TINYINT(1) NOT NULL, INDEX IDX_C4E0A61F71D4DE21 (hike_id), INDEX IDX_C4E0A61FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (role VARCHAR(100) NOT NULL, PRIMARY KEY(role)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_token (hash VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', after_url LONGTEXT DEFAULT NULL, target_url LONGTEXT NOT NULL, gateway_name VARCHAR(255) NOT NULL, PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(140) NOT NULL, date DATE NOT NULL, registration_opens DATE NOT NULL, registration_closes DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE walker (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, reference_character CHAR(1) NOT NULL, fore_name VARCHAR(100) NOT NULL, sur_name VARCHAR(100) NOT NULL, emergency_contact_number VARCHAR(100) NOT NULL, date_of_birth DATE NOT NULL, INDEX IDX_904421AF296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hike (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, name VARCHAR(140) NOT NULL, min_walkers INT NOT NULL, max_walkers INT NOT NULL, fee_per_walker NUMERIC(5, 2) NOT NULL, min_age NUMERIC(4, 2) NOT NULL, max_age NUMERIC(4, 2) NOT NULL, INDEX IDX_2301D7E471F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_payment (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, client_email VARCHAR(255) DEFAULT NULL, client_id VARCHAR(255) DEFAULT NULL, total_amount INT DEFAULT NULL, currency_code VARCHAR(255) DEFAULT NULL, details JSON NOT NULL COMMENT \'(DC2Type:json_array)\', is_completed VARCHAR(255) NOT NULL, INDEX IDX_9251F53A296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_usergroups ADD CONSTRAINT FK_4FFC8120A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE users_usergroups ADD CONSTRAINT FK_4FFC812057698A6A FOREIGN KEY (role) REFERENCES user_group (role)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE walker ADD CONSTRAINT FK_904421AF296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE team_payment ADD CONSTRAINT FK_9251F53A296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_usergroups DROP FOREIGN KEY FK_4FFC8120A76ED395');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FA76ED395');
        $this->addSql('ALTER TABLE walker DROP FOREIGN KEY FK_904421AF296CD8AE');
        $this->addSql('ALTER TABLE team_payment DROP FOREIGN KEY FK_9251F53A296CD8AE');
        $this->addSql('ALTER TABLE users_usergroups DROP FOREIGN KEY FK_4FFC812057698A6A');
        $this->addSql('ALTER TABLE hike DROP FOREIGN KEY FK_2301D7E471F7E88B');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F71D4DE21');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_usergroups');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE payment_token');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE walker');
        $this->addSql('DROP TABLE hike');
        $this->addSql('DROP TABLE team_payment');
    }
}
