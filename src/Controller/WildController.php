<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Category;

use App\Form\ProgramSearchType;
use App\Form\CategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class WildController extends AbstractController
{
    /**
     * @Route("/", name="wild_index")
     */
    public function index(Request $request) :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        foreach($programs as $program)
        {
            //echo $program->getTitle();
            $program->url = preg_replace(
                '/ /',
                '-', mb_strtolower(trim(strip_tags($program->getTitle()), "-")));
        }
        //var_dump($programs);

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            // $data contains $_POST data
            // TODO : Faire une recherche dans la BDD avec les infos de $data…
            var_dump($data);
        }

        return $this->render('wild/index.html.twig', [
                'programs' => $programs,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Getting a program
     *
     * @param string $slug The slugger
     * @Route("/program/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="program")
     * @return Response
     */

    public function  showByProgram(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        $seasons = $program->getSeason();

        return $this->render('wild/seasons.html.twig', [
            'seasons' => $seasons,
            'slug'  => $slug,
        ]);
    }

    /**
     *
     * @param int $id
     * @Route("wild/program/seasons/{id}", defaults={"id" = null}, name="episodes")
     * @return Response
     */
    public function showBySeason(int $id) : Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find the season\'s episodes.');
        }

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => ($id)]);

        $program = $season->getProgram();
        $episodes = $season->getEpisodes();

        return $this->render('wild/episodes.html.twig', [
            'season' => $season,
            'episodes'  => $episodes,
            'program' => $program,
        ]);
    }

    /**
     * @Route("/wild/{id}", name="wild_show")
     */
    public function show(Program $program): Response
    {
        return $this->render('wild/program.html.twig', [
            'program' => $program
        ]);
    }

    /**
     * @Route("/episode/{id}", name="episode_show")
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
        ]);
    }
}

