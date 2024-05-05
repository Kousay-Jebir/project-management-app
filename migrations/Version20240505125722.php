<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505125722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, assosiated_project_id INT NOT NULL, assosiated_user_id INT NOT NULL, comment_text LONGTEXT NOT NULL, INDEX IDX_9474526CEA8BA7A0 (assosiated_project_id), INDEX IDX_9474526CDA9510BD (assosiated_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CEA8BA7A0 FOREIGN KEY (assosiated_project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDA9510BD FOREIGN KEY (assosiated_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CEA8BA7A0');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CDA9510BD');
        $this->addSql('DROP TABLE comment');
    }
}
