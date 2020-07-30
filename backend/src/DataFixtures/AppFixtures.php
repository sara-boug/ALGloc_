<?php
    namespace  App\DataFixtures;

        use App\Entity\Agency;
        use App\Entity\Brand;
        use App\Entity\Category;
        use App\Entity\City;
        use App\Entity\Client;
        use App\Entity\Contract_;
        use App\Entity\Model;
        use App\Entity\Vehicle;
        use App\Entity\Wilaya;
        use Doctrine\Bundle\FixturesBundle\Fixture;
        use Doctrine\Common\Persistence\ObjectManager;
        use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
        use App\service\FileUploader; 

        class AppFixtures extends Fixture
        {
            public const data = 4; // which reffers to the number of data to insert
            private $uploader ; 
            private $encoder;

            public function __construct(UserPasswordEncoderInterface $encoder , FileUploader $uploader)
            {
                $this->encoder = $encoder;
                $this->uploader = $uploader; 
            }
            public function load(ObjectManager $manager)
            {
                $this->addWilaya($manager);
                $this->addCities($manager);
                $this->addAgencies($manager);
                $this->addBrand($manager);
                $this->addCategory($manager);
                $this->addModel($manager);
                $this->addVehicle($manager);
                $this->addClients($manager);
                $this->addContracts($manager); 

            }

            private function addWilaya(ObjectManager $manager)
            {

                for ($i = 0; $i < self::data; $i++) {
                    $wilaya = new Wilaya();
                    $wilaya->setname("wilaya" . $i);
                    $manager->persist($wilaya);
                    $manager->flush();
                    $this->addReference('wilaya' . $i, $wilaya);

                }

            }
            private function addCities(ObjectManager $manager)
            {
                for ($i = 0; $i < self::data; $i++) {

                    $city = new City();
                    $city->setName("city".$i);
                    $city->setWilaya($this->getReference('wilaya' . $i));
                    $manager->persist($city);
                    $manager->flush();

                    $this->addReference('city' . $i, $city);

                }
            }

            private function addAgencies(ObjectManager $manager)
            {
                for ($i = 0; $i < self::data; $i++) {
                    $agency = new Agency();
                    $agency->setAgencyCode("789Glmpk");
                    $agency->setPhoneNumber("067894568");
                    $agency->setEmail("emailAgency@gmail.com");
                    $agency->setAddress("bat64 cheraga");
                    $agency->setCity($this->getReference('city' . $i));
                    $manager->persist($agency);
                    $manager->flush();

                    $this->addReference('agency' . $i, $agency);
                }

            }
            private function addBrand(ObjectManager $manager)
            {
                for ($i = 0; $i < self::data; $i++) {
                    $brand = new Brand();
                    $brand->setname("brand" . $i);
                    $manager->persist($brand);
                    $manager->flush();

                    $this->addReference('brand' . $i, $brand);

                }

            }
            private function addCategory(ObjectManager $manager)
            {
                for ($i = 0; $i < self::data; $i++) {
                    $category = new Category();
                    $category->setname("category" . $i);
                    $manager->persist($category);
                    $manager->flush();
                    $this->addReference('category' . $i, $category);
                }

            }

            private function addModel(ObjectManager $manager)
            {
                for ($i = 0; $i < self::data; $i++) {
                    $model = new Model();
                    $model->setname("model" . $i);
                    $model->setbrand($this->getReference('brand' . $i));
                    $model->setcategory($this->getReference('category' . $i));
                    $manager->persist($model);
                    $manager->flush();
                    $this->addReference('model' . $i, $model);

                }

            }

            private function addVehicle(ObjectManager $manager)
            {
                for ($i = 0; $i < self::data; $i++) {
                    $vehicle = new Vehicle();
                    $vehicle->setRegistrationNumber("16B199816A");
                    $vehicle->setRentalPrice(2500);
                    $vehicle->setInssurancePrice(300);
                    $vehicle->setSuitcaseNumber(3);
                    $vehicle->setPassengerNumber(2);
                    $vehicle->setDeposit(250);
                    $vehicle->setState("new");
                    $vehicle->setStatus("allocated");
                    $vehicle->setGearbox("automatique");
                    $vehicle->setImage("car.jpeg");
                    $vehicle->setModel($this->getReference('model' . $i));
                    $vehicle->setagency($this->getReference('agency' . $i));
                    $manager->persist($vehicle);
                    $manager->flush();

                    $this->addReference('vehicle'.$i, $vehicle);
                }
            }

            private function addClients(ObjectManager $manager)
            {
                for ($i = 0; $i < self::data; $i++) {
                    $client = new Client();
                    $client->setFullname("fullname" . $i);
                    $client->setemail("email" . $i . "@gmail.com");
                    $client->setphoneNumber("0692783" . $i);
                    $client->setaddress("address bat num" . $i);
                    $client->setlicenseNumber("l123" . $i);
                    $client->setPassword($this->encoder->encodePassword($client, "psw" . $i));
                    $client->setcity($this->getReference('city' . $i));
                    $manager->persist($client);
                    $manager->flush();
                    $this->addReference('client'.$i , $client); 


                }

            }

            public function addContracts(ObjectManager $manager){ 
                for ($i = 0; $i < self::data; $i++) {
                     $contract = new Contract_() ; 
                     $contract->setDate('now'); 
                     $contract->setDeparture($i.'-1-2017');
                     $contract->setArrival($i.'-2-2017');
                     $contract->setVehicle($this->getReference('vehicle'.$i)); 
                     $contract->setclient($this->getReference('client'.$i)); 
                     $manager->persist($contract); 
                     $manager->flush();
                     $this->addReference('contract'.$i , $contract); 

                }
            }
            
        }
