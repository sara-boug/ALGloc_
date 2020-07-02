<?php

    declare(strict_types=1);

    namespace DoctrineMigrations;
    use Doctrine\DBAL\Schema\Schema;
    use Doctrine\Migrations\AbstractMigration;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; 
    use App\Entity\Admin_;
    use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface; 

    /**
     * Auto-generated Migration: Please modify to your needs!
     */
    final class Version2 extends AbstractMigration
    {  

        public function getDescription() : string
        {
            return '';
        }

        public function up(Schema $schema) : void
        {  
           $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');          
           
           
           $this ->addSql('INSERT INTO `wilaya` ( id , name_ ) VALUES
                    ( 1,"Adrar"),
                    ( 2,"Chlef"),
                    ( 3,"Laghouat"),
                    ( 4,"Oum El Bouaghi"),
                    ( 5,"Batna"),
                     (6, "Béjaïa"),
                    ( 7,"Biskra"),
                    ( 8,"Béchar"),
                    ( 9,"Blida"),
                    ( 10,"Bouira"),
                    ( 11,"Tamanrasset"),
                    ( 12,"Tébessa"),
                    ( 13,"Tlemcen"),
                    ( 14,"Tiaret"),
                    ( 15,"Tizi Ouzou"),
                    ( 16,"Alger"),
                    ( 17,"Djelfa"),
                    ( 18,"Jijel"),
                    ( 19,"Sétif"),
                    ( 20,"Saïda"),
                    ( 21,"Skikda"),
                    ( 22,"Sidi Bel Abbès"),
                    ( 23,"Annaba"),
                    ( 24,"Guelma"),
                    ( 25,"Constantine"),
                    ( 26,"Médéa"),
                    ( 27,"Mostaganem"),
                    ( 28,"MSila"),
                    ( 29,"Mascara"),
                    ( 30,"Ouargla"),
                    ( 31,"Oran"),
                    ( 32,"El Bayadh"),
                    ( 33,"Illizi"),
                    ( 34,"Bordj Bou Arreridj"),
                    ( 35,"Boumerdès"),
                    ( 36,"El Tarf"),
                    ( 37,"Tindouf"),
                    ( 38,"Tissemsilt"),
                    ( 39,"El Oued"),
                    ( 40,"Khenchela"),
                    ( 41,"Souk Ahras"),
                    ( 42,"Tipaza"),
                    ( 43,"Mila"),
                    ( 44,"Aïn Defla"),
                    (45,"Naâma"),
                    ( 46,"Aïn Témouchent"),
                    ( 47,"Ghardaïa"),
                    ( 48,"Relizane");'); 
            // Reno , pegeo , skoda , folkswagen , hyundai , kea , nisan , from 
            // popular car brands  in algeria 
            $this -> addSql('INSERT INTO model ( name_) values 
                ("reno") , ("pegeot") , ("skoda") , ("folkswagen") , ("hyundai"),("kea") , ("nisan")'); 
            // inserting typical car  categories
            $this->addSql(' INSERT INTO category (name_) values 
             ("mini") , ("intermidiate") , ("suv") ,("economy") , ("luxury"),("compact") , ("offroad 4x4") 
             , ("compact cabrio"), ("pick up")
            ');
    
        
         
            $this->addSql('INSERT INTO City (name_, wilaya) VALUES
            ( "Algiers" ,16),
            ( "Boumerdes",35),
            ( "Oran" , 31),
            ( "Tebessa",12),
            ( "Constantine",25),
            ( "Biskra",7),
            ( "Setif", 19),
            ( "Batna",5),
            ( "Bab-Ezzaouar" ,16),
            ( "Annaba",23),
            ( "Sidi-Bel-Abbess",22),
            ( "Blida" , 9)' ) ; 

        }
        public function down(Schema $schema) : void
        {
        }
    }


?> 