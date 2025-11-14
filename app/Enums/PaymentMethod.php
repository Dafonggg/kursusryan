<?php
// app/Enums/PaymentMethod.php
namespace App\Enums;

enum PaymentMethod: string { case Cash='cash'; case Transfer='transfer'; case Qris='qris'; case Gateway='gateway'; }
