<?php
namespace NYPL\HoldRequestResultConsumer\Model;

use NYPL\Starter\Model;
use NYPL\Starter\Model\ModelTrait\TranslateTrait;

abstract class DataModel extends Model
{
    use TranslateTrait;
}
