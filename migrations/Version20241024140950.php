<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024140950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_recipe DROP FOREIGN KEY FK_D5607B4C12469DE2');
        $this->addSql('ALTER TABLE category_recipe DROP FOREIGN KEY FK_D5607B4C59D8A214');
        $this->addSql('DROP TABLE category_recipe');
        $this->addSql('ALTER TABLE recipe ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_DA88B13712469DE2 ON recipe (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_recipe (category_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_D5607B4C59D8A214 (recipe_id), INDEX IDX_D5607B4C12469DE2 (category_id), PRIMARY KEY(category_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category_recipe ADD CONSTRAINT FK_D5607B4C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_recipe ADD CONSTRAINT FK_D5607B4C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13712469DE2');
        $this->addSql('DROP INDEX IDX_DA88B13712469DE2 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP category_id');
    }
}
