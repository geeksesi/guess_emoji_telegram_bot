<?php

use App\Helper\Normalizer;

test("remove spaces", function () {
    expect(Normalizer::run("سلام دنیا"))->toEqual("سلامدنیا");
});
