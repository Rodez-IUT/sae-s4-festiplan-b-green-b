<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace application;

//use controllers\CreationSceneController;
use controllers\FestivalsAjoutsController;
use controllers\CreationCompteController;
use controllers\CreationSpectacleController;
use controllers\ErreurBDController;
use controllers\GestionFestivalsController;
use controllers\GestionSpectaclesController;
use controllers\GrijController;
use controllers\HomeController;
use controllers\AuthentificationController;
use controllers\InformationCompteController;
use controllers\InfoSpectacleController;
use controllers\ListeTousSpectaclesController;

use controllers\ModifInfoPersoController;
use controllers\PlanificationController;
use controllers\SpectacleAjoutsController;
//use services\CreationSceneService;
use services\FestivalsAjoutsService;
use services\CreationGriJServices;
use services\GestionSpectaclesService;
use services\InformationCompteService;
use controllers\ListeFestivalController;
use controllers\ListeSpectacleController;
use services\AccueilService;
use services\AuthentificationServices;
use services\CreationCompteServices;

use services\CreationSpectacleServices;
use services\ErreurBDService;
use services\GestionFestivalsServices;
use services\InfoSpectacleService;
use services\ListeFestivalServices;
use services\ListeSpectacleServices;
use services\ListeTousSpectaclesServices;
use services\ModifInfoPersoService;
use services\PlanificationServices;
use services\SpectacleAjoutsService;
use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

/**
 *  The controller factory
 */
class DefaultComponentFactory implements ComponentFactory
{

    /**
     * @param string $controller_name the name of the controller to instanciate
     * @return mixed the controller
     * @throws NoControllerAvailableForNameException when controller is not found
     */
    public function buildControllerByName(string $controller_name): mixed {
        return match ($controller_name) {
            "Home" => $this->buildHomeController(),
            "ErreurBD" => $this->buildErreurBDController(),
            "Authentification" => $this->buildAuthentificationController(),
            "CreationCompte" => $this->buildCreationCompteController(),
            "CreationFestival" => $this->buildCreationFestivalController(),
            "ListeFestival" => $this->buildListeFestivalController(),
            "CreationSpectacle" => $this->buildCreationSpectacleController(),
            "ListeSpectacle" => $this->buildListeSpectacleController(),
            "GestionFestivals" => $this->buildGestionFestivalsController(),
            "InformationCompte" => $this->buildInformationCompteController(),
            "CreationGriJ" => $this->buildGriJController(),
            "ListeTousSpectacles" => $this->buildListeTousSpectaclesController(),
            "Planification" => $this->buildPlanificationController(),
            "InfoSpectacle" => $this->buildInfoSpectacleController(),
            "FestivalAjouts" => $this->buildFestivalAjoutsController(),
            "SpectacleAjouts" => $this->buildSpectacleAjoutsController(),
            "ModifInfoPerso" => $this->buildModifInfoPersoController(),
            "GestionSpectacles" => $this->buildGestionSpectaclesController(),
            //"CreationScene" => $this->buildCreationSceneController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    /**
     * @param string $service_name the name of the service
     * @return mixed the created service
     * @throws NoServiceAvailableForNameException when service is not found
     */
    public function buildServiceByName(string $service_name): mixed
    {
        return new NoServiceAvailableForNameException($service_name);
    }


    /**
     * @return HomeController
     */
    private function buildHomeController(): HomeController
    {
        return new HomeController(new AccueilService());
    }

    /**
     * @return AuthentificationController
     */
    private function buildAuthentificationController(): AuthentificationController
    {
        return new AuthentificationController(new AuthentificationServices());
    }

    /**
     * @return CreationCompteController
     */
    private function buildCreationCompteController(): CreationCompteController
    {
        return new CreationCompteController(new CreationCompteServices());
    }

    /**
     * @return GestionFestivalsController
     */
    private function buildCreationFestivalController(): GestionFestivalsController
    {
        return new GestionFestivalsController(new GestionFestivalsServices());
    }

    /**
     * @return ListeFestivalController
     */
    private function buildListeFestivalController(): ListeFestivalController
    {
        return new ListeFestivalController(new ListeFestivalServices());
    }

    /**
     * @return ErreurBDController
     */
    private function buildErreurBDController(): ErreurBDController
    {
        return new ErreurBDController();
    }

    /**
     * @return GestionFestivalsController
     */
    private function buildGestionFestivalsController(): GestionFestivalsController
    {
        return new GestionFestivalsController(new GestionFestivalsServices());
    }

    /**
     * @return CreationSpectacleController
     */
    private function buildCreationSpectacleController(): CreationSpectacleController
    {
        return new CreationSpectacleController(new CreationSpectacleServices());
    }

    /**
     * @return ListeSpectacleController
     */
    private function buildListeSpectacleController(): ListeSpectacleController
    {
        return new ListeSpectacleController(new ListeSpectacleServices());
    }

    private function buildInformationCompteController(): InformationCompteController 
    {
        return new InformationCompteController(new InformationCompteService());
    }

    private function buildGriJController() : GrijController
    {
        return new GrijController(new CreationGriJServices());
    }

    private function buildListeTousSpectaclesController(): ListeTousSpectaclesController
    {
        return new ListeTousSpectaclesController(new ListeTousSpectaclesServices());
    }

    /**
     * @return PlanificationController
     */
    private function buildPlanificationController(): PlanificationController
    {
        return new PlanificationController(new PlanificationServices());
    }

    private function buildInfoSpectacleController(): InfoSpectacleController
    {
        return new InfoSpectacleController(new InfoSpectacleService());
    }

    private function buildFestivalAjoutsController(): FestivalsAjoutsController
    {
        return new FestivalsAjoutsController(new FestivalsAjoutsService());
    }

    private function buildModifInfoPersoController(): ModifInfoPersoController
    {
        return new ModifInfoPersoController(new ModifInfoPersoService());
    }

    private function buildSpectacleAjoutsController(): SpectacleAjoutsController
    {
        return new SpectacleAjoutsController(new SpectacleAjoutsService());
    }

    private function buildGestionSpectaclesController(): GestionSpectaclesController
    {
        return new GestionSpectaclesController(new GestionSpectaclesService());
    }

    //private function buildCreationSceneController(): CreationSceneController
    //{
    //    return new CreationSceneController(new CreationSceneService());
    //}


}