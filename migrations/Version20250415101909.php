<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415101909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE color_bead (color_id INT NOT NULL, bead_id INT NOT NULL, INDEX IDX_B5992DBC7ADA1FB5 (color_id), INDEX IDX_B5992DBC506A3ED0 (bead_id), PRIMARY KEY(color_id, bead_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color_bead ADD CONSTRAINT FK_B5992DBC7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color_bead ADD CONSTRAINT FK_B5992DBC506A3ED0 FOREIGN KEY (bead_id) REFERENCES bead (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE beads
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_C84E16F8989D9B62 ON bead
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bead DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mixes DROP bead_id, CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE beads (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, stock INT DEFAULT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, userid INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color_bead DROP FOREIGN KEY FK_B5992DBC7ADA1FB5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color_bead DROP FOREIGN KEY FK_B5992DBC506A3ED0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE color_bead
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bead ADD slug VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_C84E16F8989D9B62 ON bead (slug)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mixes MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON mixes
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mixes ADD bead_id INT DEFAULT NULL, CHANGE id id INT NOT NULL
        SQL);
    }
}
