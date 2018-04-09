<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180409164308 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_group (role VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(role)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_usergroups (user_id INT NOT NULL, role VARCHAR(100) NOT NULL, INDEX IDX_4FFC8120A76ED395 (user_id), INDEX IDX_4FFC812057698A6A (role), PRIMARY KEY(user_id, role)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_usergroups ADD CONSTRAINT FK_4FFC8120A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE users_usergroups ADD CONSTRAINT FK_4FFC812057698A6A FOREIGN KEY (role) REFERENCES user_group (role)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_usergroups DROP FOREIGN KEY FK_4FFC812057698A6A');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE users_usergroups');
    }
}
