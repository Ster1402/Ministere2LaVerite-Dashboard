<?php


namespace App\DTOs\users;

use App\Models\Assembly;

class UpdateUserDTO
{
    public ?BaptismDTO $baptism;

    public function __construct(
        public string $name,
        public string $email,
        public string $gender,
        public ?string $password,
        public string $profession,
        public ?string $profession_details = null,
        public ?string $surname = null,
        public ?string $phoneNumber = null,
        public ?string $profile_photo_path = null,
        public ?string $dateOfBirth = null,
        public ?string $residence = null,
        public ?string $antecedent = null,
        public ?bool $isActive = true,
        public ?bool $isDisciplined = true,
        public ?string $arrivalDate = null,
        public ?string $maritalStatus = null,
        public ?int $numberOfChildren = 0,
        public ?bool $sterileWoman = false,
        public ?string $seriousIllnesses = null,
        public ?string $comment = null,
        public ?Assembly $assembly = null)
    {
    }
}
