<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 21:07
 */

namespace Cinelaf\Services;


class FilmSession
{

    public $session_key_new_film;

    private $titolo;
    private $regista;
    private $anno;
    private $locandina;
    private $type;



    /**
     * FilmSession constructor.
     *
     * Recupera i dati già presenti in sessione
     *
     */
    public function __construct()
    {

        $this->session_key_new_film = config('cinelaf.sessions_key.film.new');

        $currentFilm = $this->get();

        $this->titolo = $currentFilm['titolo'];
        $this->regista = $currentFilm['regista'];
        $this->anno = $currentFilm['anno'];
        $this->locandina = $currentFilm['locandina'];
        $this->type = $currentFilm['type'];

    }


    public function get()
    {
        return session()->get($this->session_key_new_film);
    }


    /**
     * Salva le modifiche ai dati in sessione
     */
    public function save()
    {
        
        $film = [
            'titolo' => $this->titolo,
            'regista' => $this->regista,
            'anno' => $this->anno,
            'locandina' => $this->locandina,
            'type' => $this->type,
            'user_id' => auth()->user()->id
        ];
        
        session([$this->session_key_new_film => $film]);

        return $film;
        
    }


    /**
     * Elimina i dati in sessione
     */
    public function delete()
    {
        session()->forget($this->session_key_new_film);
    }


    /**
     * Alias per delete
     */
    public function reset()
    {
        $this->delete();
    }


    /**
     * @param $titolo
     *
     * @return FilmSession
     */
    public function setTitolo($titolo): FilmSession
    {
        $this->titolo = $titolo;
        return $this;
    }

    /**
     * @param $regista
     *
     * @return FilmSession
     */
    public function setRegista($regista): FilmSession
    {
        $this->regista = $regista;
        return $this;
    }


    /**
     * @param $anno
     *
     * @return FilmSession
     */
    public function setAnno($anno): FilmSession
    {
        $this->anno = $anno;
        return $this;
    }


    /**
     * @param $locandina
     *
     * @return FilmSession
     */
    public function setLocandina($locandina): FilmSession
    {
        $this->locandina = $locandina;
        return $this;
    }


    /**
     * @param $type
     *
     * @return FilmSession
     */
    public function setType($type): FilmSession
    {
        $this->type = $type;
        return $this;
    }



}