<?php

namespace APP\Services;
 use App\Repositories\JournalRepository;
 use App\Core\Singleton;
use App\Entities\JournalEntity;

class JournalService extends Singleton implements IjournalInterface
{
    

    private IJournalRepository $IJournalRepository;
    
    private function __construct(IJournalRepository $IJournalRepository)
    {
        $this->IJournalRepository = $IJournalRepository;
    }

    
    public function create(JournalEntity $journalEntity )
    {
        return $this->JournalRepository->insert($journalEntity);   
    }
}


