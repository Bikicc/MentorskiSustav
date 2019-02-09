<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190203174056 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_subjects (id INT AUTO_INCREMENT NOT NULL, subject_code VARCHAR(50) NOT NULL, user_email VARCHAR(50) NOT NULL, status VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE users_subjects');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users_subjects (user_email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, subject_code VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_D064B2CD550872C (user_email), INDEX IDX_D064B2CDE5DFB443 (subject_code), PRIMARY KEY(user_email, subject_code)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_subjects ADD CONSTRAINT FK_D064B2CD550872C FOREIGN KEY (user_email) REFERENCES user (email)');
        $this->addSql('ALTER TABLE users_subjects ADD CONSTRAINT FK_D064B2CDE5DFB443 FOREIGN KEY (subject_code) REFERENCES subject (code)');
        $this->addSql('DROP TABLE user_subjects');
    }
}
