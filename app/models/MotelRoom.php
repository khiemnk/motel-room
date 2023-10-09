<?php

class MotelRoom
{
    private $id;
    private $name;
    private $address;
    private $description;
    private $image;
    private $contact;
	private $price;
    private $summary;
    private $rating;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 * @return self
	 */
	public function setName($name): self {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param mixed $address
	 * @return self
	 */
	public function setAddress($address): self {
		$this->address = $address;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $desciption
	 * @return self
	 */
	public function setDesciption($description): self {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param mixed $image
	 * @return self
	 */
	public function setImage($image): self {
		$this->image = $image;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getContact() {
		return $this->contact;
	}

	/**
	 * @param mixed $contact
	 * @return self
	 */
	public function setContact($contact): self {
		$this->contact = $contact;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param mixed $price
	 * @return self
	 */
	public function setPrice($price): self {
		$this->price = $price;
		return $this;
	}

    public function getSummary() {
        return $this->summary;
    }

    public function setSummary($summary): self {
        $this->summary = $summary;
        return $this;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }
}
