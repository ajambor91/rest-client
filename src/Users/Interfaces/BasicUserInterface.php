<?php

namespace AJ\Rest\Users\Interfaces;

 interface BasicUserInterface {
     public function setPassword(string $password);
     public function getPassword(): string;
     public function setName(string $name);
     public function getName():string;
 }