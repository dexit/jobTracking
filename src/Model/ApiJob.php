<?php

namespace App\Models;

use DateTime;

class ApiJob
{
    private  string $noInfoStr = '<span class="text-warning">Renseignement indisponible</span>';
    private  string $source;
    private  string $company;
    private  string $location;
    private  string $typeContrat;
    private  string $description;
    private  string $title;
    private  string $link;
    private  string $created;
    private  string $id;




    /**
     * Set the value of source
     *
     * @return  self
     */
    public function setSource(string $source)
    {

        $this->source = $this->setDefaultFieldName($source);

        return $this;
    }

    /**
     * Set the value of company
     *
     * @return  self
     */
    public function setCompany(string $company)
    {
        $this->company = $this->setDefaultFieldName($company);

        return $this;
    }

    /**
     * Set the value of location
     *
     * @return  self
     */
    public function setLocation(string $location)
    {
        $this->location = $this->setDefaultFieldName($location);

        return $this;
    }

    /**
     * Set the value of type_contrat
     *
     * @return  self
     */
    public function setTypeContrat(string $type_contrat)
    {
        $this->typeContrat = $this->setDefaultFieldName($type_contrat);

        return $this;
    }

    public function getJobArray(): array
    {
        $created = new DateTime($this->created);

        return [
            'id' => $this->id,
            'source' => $this->source,
            'company' => $this->company,
            'description' => $this->description,
            'title' => $this->title,
            'location' =>  $this->location,
            'link' =>  $this->link,
            'type_contrat' => $this->typeContrat,
            'created' => $created->format('d/m/y')
        ];
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription(string $description)
    {
        $this->description = $this->setDefaultFieldName($description);

        return $this;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle(string $title)
    {
        $this->title = $this->setDefaultFieldName($title);

        return $this;
    }

    /**
     * Set the value of link
     *
     * @return  self
     */
    public function setLink(string $link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Set the value of created
     *
     * @return  self
     */
    public function setCreated(string $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getNoInfoStr(): string
    {
        return $this->noInfoStr;
    }

    private function  setDefaultFieldName($field){
        if(empty($field)){
            return $this->getNoInfoStr();
        }
        return $field;
    }
}
