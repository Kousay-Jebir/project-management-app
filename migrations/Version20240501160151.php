<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501160151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ADD team_leader_id INT NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FC4105033 FOREIGN KEY (team_leader_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61FC4105033 ON team (team_leader_id)');
        $this->addSql('ALTER TABLE user_team DROP role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FC4105033');
        $this->addSql('DROP INDEX IDX_C4E0A61FC4105033 ON team');
        $this->addSql('ALTER TABLE team DROP team_leader_id');
        $this->addSql('ALTER TABLE user_team ADD role VARCHAR(255) DEFAULT NULL');
    }
}
