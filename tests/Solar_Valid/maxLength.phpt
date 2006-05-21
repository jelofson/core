--TEST--
Solar_Valid::maxLength()
--FILE---
<?php
// include ../_prepend.inc
if (is_readable(dirname(dirname(__FILE__)) . '/_prepend.inc')) {
    require dirname(dirname(__FILE__)) . '/_prepend.inc';
}

// include ./_prepend.inc
if (is_readable(dirname(__FILE__) . '/_prepend.inc')) {
    require dirname(__FILE__) . '/_prepend.inc';
}

// ---------------------------------------------------------------------

$valid = Solar::factory('Solar_Valid');

$len = strlen("I am the very model");

// good
$test = array(
	0,
	"I am",
	"I am the very model",
);
foreach ($test as $val) {
    $assert->setLabel("'$val'");
    $assert->isTrue($valid->maxLength($val, $len));
}

// bad, or are blank
$test = array(
	"", " ",
	"I am the very model of a modern",
	"I am the very model of a moden Major-General",
);
foreach ($test as $val) {
    $assert->setLabel("'$val'");
    $assert->isFalse($valid->maxLength($val, $len));
}

// blanks allowed
$test = array(
	"", ' ',
	"I am",
	"I am the very model",
);
foreach ($test as $val) {
    $assert->setLabel("'$val'");
    $assert->isTrue($valid->maxLength($val, $len, Solar_Valid::OR_BLANK));
}



// ---------------------------------------------------------------------

// include ./_append.inc
if (is_readable(dirname(__FILE__) . '/_append.inc')) {
    require dirname(__FILE__) . '/_append.inc';
}
// include ../_append.inc
if (is_readable(dirname(dirname(__FILE__)) . '/_append.inc')) {
    require dirname(dirname(__FILE__)) . '/_append.inc';
}
?>
--EXPECT--