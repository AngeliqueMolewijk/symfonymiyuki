<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419063928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE bead_mix (bead_source INT NOT NULL, bead_target INT NOT NULL, INDEX IDX_B8F878F8709D2D09 (bead_source), INDEX IDX_B8F878F869787D86 (bead_target), PRIMARY KEY(bead_source, bead_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bead_mix ADD CONSTRAINT FK_B8F878F8709D2D09 FOREIGN KEY (bead_source) REFERENCES bead (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bead_mix ADD CONSTRAINT FK_B8F878F869787D86 FOREIGN KEY (bead_target) REFERENCES bead (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE bead_mix DROP FOREIGN KEY FK_B8F878F8709D2D09
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bead_mix DROP FOREIGN KEY FK_B8F878F869787D86
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE bead_mix
        SQL);
    }
}
