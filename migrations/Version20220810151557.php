<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810151557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wineries DROP FOREIGN KEY wineries_ibfk_1');
        $this->addSql('ALTER TABLE wineries ADD CONSTRAINT FK_E903FF1DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wineries DROP FOREIGN KEY FK_E903FF1DA76ED395');
        $this->addSql('ALTER TABLE wineries ADD CONSTRAINT wineries_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE');
    }
}
