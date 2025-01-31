<?php
namespace App\Service;

use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function PHPUnit\Framework\throwException;

class CsvImporter
{
    private ParticipantService $participantService;
    private SiteService $siteService;
    private UserPasswordHasherInterface $passwordHasher;
    private string $directory;

    public function __construct(
        ParticipantService $participantService,
        SiteService $siteService,
        UserPasswordHasherInterface $passwordHasher,
        string $directory
    ) {
        $this->participantService = $participantService;
        $this->siteService = $siteService;
        $this->passwordHasher = $passwordHasher;
        $this->directory = $directory;
    }

    public function import(string $filePath, User $adminUser): array
    {
        $fullPath = $this->directory . '/' . $filePath;

        if (!file_exists($fullPath)) {
            return ["Le fichier n'a pas été trouvé. Veuillez réessayer."];
        }

        $handle = fopen($fullPath, 'r');
        if ($handle === false) {
            return ["Impossible d’ouvrir le fichier. Veuillez réessayer."];
        }

        $errors = [];
        $row = 0;
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $row++;
            if ($row === 1) continue; // Ignore first line

            // Check columns number CSV
            if (count($data) < 5) {
                $errors[] = "Ligne $row : Données incomplètes.";
                continue;
            }

            [$firstname, $lastname, $pseudo, $email, $siteName] = array_map('trim', $data);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Ligne $row : Email invalide ($email)";
                continue;
            }

            $site = $this->siteService->getAllByWord($siteName);
            if (!$site) {
                $site = new Site();
                $site->setName($siteName);
                $this->siteService->store($site);
            } else {
                $site = $site[0];
            }

            $participant = new Participant();
            $participant->setFirstname($firstname);
            $participant->setLastname($lastname);
            $participant->setPseudo($pseudo);
            $participant->setEmail($email);
            $participant->setSite($site);
            $participant->setIsAdmin(false);
            $participant->setRoles(['ROLE_USER']);

            // Pwd generate ENI-(year)
            $defaultPassword = 'ENI-' . date('Y');
            $hashedPassword = $this->passwordHasher->hashPassword($participant, $defaultPassword);
            $participant->setPassword($hashedPassword);

            $participant->setIsActive(true);

            $allParticipants = $this->participantService->getAllParticipants();

            $existingEmails = [];
            $existingPseudos = [];
            foreach ($allParticipants as $p) {
                $existingEmails[$p->getEmail()] = true;
            }
            foreach ($allParticipants as $p) {
                $existingPseudos[$p->getPseudo()] = true;
            }

            if (isset($existingEmails[$email])) {
                $errors[] = "Ligne $row : Cet email est déjà utilisé.";
                continue;
            }
            if (isset($existingPseudos[$pseudo])) {
                $errors[] = "Ligne $row : Ce pseudo est déjà utilisé.";
                continue;
            }

            $this->participantService->storeOrUpdateParticipant($participant, null, null, $adminUser);

        }

        fclose($handle);
        return $errors;
    }
}

