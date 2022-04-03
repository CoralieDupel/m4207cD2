<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Document;



class ServeurController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request): Response
    {
        return $this->render('Serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
        
       
      
    }

    /**
     * @Route("/identification", name="identification")
     */
    
    public function identification(Request $request,SessionInterface $session): Response
    {
		
		$session = $request->getSession();
		$session->clear();
		
        return $this->redirectToRoute('login');
    }
    
   
    
   
        
    /**
     * @Route("/validation", name="validation")
     */
    public function validation(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $login=$request->request->get("login");
       $password=$request->request->get("password");
       dump($login,$password);
		$val=0;

        $reponse = $manager -> getRepository(Utilisateur::class) -> findOneBy([ 'login' => $login]);
            if($reponse==NULL){
                $validation="utilisateur non existant";
               
               

            }else{
                $motdepass=$reponse -> getPassword();
                
                if($motdepass==$password){
                    $validation="validation";
                     $val=1;
				    dump($val);
                    $session = new Session();
                    $userId=$reponse ->getId();
                    $userId = $session -> set('userId',$userId);
                  
                    $session->set('idUser', $reponse->getId());
                    dump($session->get('nameUser'));
                   return $this->redirectToRoute('Affichage');
                }else{
                    $validation="Mot de passe invalide";
                }
            
            }
            
            return $this->render('Serveur/validation.html.twig', [
                    'controller_name' => 'ServeurController',
                    'login'=> $login,
                    'password'=> $password, 
                    'validation'=> $validation,
                    
                    ]);

    }

    /**
    * @Route("/supprimerUtilisateur/{id}",name="supprimer_Utilisateur")
    */
    public function supprimerUtilisateur(EntityManagerInterface $manager,Utilisateur $editutil): Response {
        $manager->remove($editutil);
        $manager->flush();
        // Affiche de nouveau la liste des utilisateurs
        return $this->redirectToRoute ('Affichage');
    }
    /**
     * @Route("/inscription", name="creationutilisateur")
     */
    public function inscription(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    { 
        $session = $request->getSession();
		
		if($session->get('userId')==NULL){
			
			return $this->redirectToRoute('login');
		}else{	
        return $this->render('Serveur/creationutilisateur.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }
    } 
     /**
     * @Route("/newutilisateur", name="newutilisateur")
     */
    public function newutilisateur(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $session = $request->getSession();
		
		if($session->get('userId')==NULL){
			
			return $this->redirectToRoute('login');
		}else{	
        $newlogin=$request->request->get("newlogin");
        $newpassword=$request->request->get("newpassword1");
        $newutilisateur= new Utilisateur();
        $newutilisateur->setlogin($newlogin);
        $newutilisateur->setpassword($newpassword);
        $manager -> persist($newutilisateur);
        $manager ->flush();
        return $this->redirectToRoute ('Affichage');
    }
          
    }
     /**
     * @Route("/Affichage", name="Affichage")
     */
    public function Affichage(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        if($session->get('userId')==NULL){
			
			return $this->redirectToRoute('login');
		}else{	
        $mesUtilisateurs = $manager->getRepository(Utilisateur::class)->findAll();
        return $this->render('Serveur/Affichage.html.twig',['lst_utilisateurs' => $mesUtilisateurs]);
            
        }

        
    } 
     /**
     * @Route("/session", name="session")
     */
    public function session(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $Utilisateur->getId(); 
        $vs = $session -> get('login');
        $userId = $session -> get('userId');
       
        $Utilisateur = $manager -> getRepository(Utilisateur::class)->findOneById($userId);
        if ($Utilisateur==NULL){
            return $this->redirectToRoute ('identification');
        }else{
            return $this->redirectToRoute ('Affichage');
        }

        $val = 44;
        $session -> set('situation',$val);
        $session -> clear ();

        
          
    }
     /**
     * @Route("/menu", name="menu")
     */
    public function menu(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        
            return $this->redirectToRoute ('login');
        

        $val = 44;
        $session -> set('situation',$val);

        
          
    }
    
     /**
     * @Route("/listefichier", name="listefichier")
     */
    public function listefichier(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        if($session->get('userId')==NULL){

			return $this->redirectToRoute('login');
		}else{	
            $mesDocuments = $manager->getRepository(Document::class)->findAll();
        return $this->render('Serveur/listefichier.html.twig',['lst_fichier' => $mesDocuments]);

        }


    } 
      /**
     * @Route("/ajoutfichier", name="ajoutfichier")
     */
    public function ajoutfichier(Request $request,EntityManagerInterface $manager,SessionInterface $session,ManagerRegistry $doctrine): Response
    {
        if($session->get('userId')==NULL){

			return $this->redirectToRoute('login');
		}else{	
            return $this->render('Serveur/ajoutfichier.html.twig', [
				'Fichiers' => $doctrine->getRepository(Fichiers::class)->findAll(),
				'Utilisateur' => $doctrine->getRepository(Utilisateur::class)->findAll(),
			]); 
        }


    } 
     /**
     * @Route("/uploadDocument", name="uploadDocument")
     */
    public function uploadDocument(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $em): Response
    {

		if($session->get('userId')==NULL){

			return $this->redirectToRoute('login');

		}else{	

			$doc = new Document();
			$doc->setLogin($request->request->get('login'));
			$doc->setChemin("toto");
			if($request->request->get('choixBox')=="on"){
				$doc->setActif(1);
			}else{
				$doc->setActif(0);
			}
			$doc->setCreatedAt(new \DatetimeImmutable);
			$doc->setType($doctrine->getRepository(Genre::class)->findOneById($request->request->get('genre')));
			$em->persist($doc);
			$em->flush();
			//maj de la table acces
			$acces = new Acces();
			$acces->setDocument($doc);
			$acces->setAutorisation($doctrine->getRepository(Autorisation::class)->findOneById(2));
			$acces->setUtilisateur($doctrine->getRepository(User::class)->findOneById($session->get('idUser')));
			$em->persist($acces);
			$em->flush();
			if($request->request->get('user')!="null"){
				$acces = new Acces();
				$acces->setDocument($doc);
				$acces->setAutorisation($doctrine->getRepository(Autorisation::class)->findOneById(2));
				$acces->setUtilisateur($doctrine->getRepository(User::class)->findOneById($request->request->get('user')));
				$em->persist($acces);
				$em->flush();
			}
			//5) sinon on renvoie la page demandée.
			return $this->redirectToRoute('listefichier');
		}
    }
}

