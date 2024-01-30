<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527190806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_image DROP INDEX IDX_27FFFF07A76ED395, ADD UNIQUE INDEX UNIQ_27FFFF07A76ED395 (user_id)');
        $this->addSql('ALTER TABLE user_image ADD image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_image DROP INDEX UNIQ_27FFFF07A76ED395, ADD INDEX IDX_27FFFF07A76ED395 (user_id)');
        $this->addSql('ALTER TABLE user_image DROP image');
    }
}
