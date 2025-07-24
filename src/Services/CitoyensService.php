<?php
namespace App\Services;
use App\Entities\CitoyensEntity;
use App\Repositories\ICitoyensRepository;
use App\Core\Singleton;
class CitoyensService extends Singleton implements ICitoyensService
 {
    

    private ICitoyensRepository $IcitoyensRepository;

    public function __construct(ICitoyensRepository $IcitoyensRepository)
    {
        $this->IcitoyensRepository = $IcitoyensRepository;
    }

    public function getCitoyenByNumCni(string $numcni): ?CitoyensEntity
    {
        return $this->citoyensRepository->selectBynum($numcni);
    }


}