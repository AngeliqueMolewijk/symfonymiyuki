<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507110340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_bead (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, bead_id INT DEFAULT NULL, stock INT DEFAULT NULL, INDEX IDX_B2CF5A31A76ED395 (user_id), INDEX IDX_B2CF5A31506A3ED0 (bead_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_bead ADD CONSTRAINT FK_B2CF5A31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_bead ADD CONSTRAINT FK_B2CF5A31506A3ED0 FOREIGN KEY (bead_id) REFERENCES bead (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_bead DROP FOREIGN KEY FK_B2CF5A31A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_bead DROP FOREIGN KEY FK_B2CF5A31506A3ED0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_bead
        SQL);
    }
}
