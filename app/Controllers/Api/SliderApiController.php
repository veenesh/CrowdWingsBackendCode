<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SliderModel;

class SliderApiController extends BaseController
{
    public function index()
    {
        $sliderModel = new SliderModel();
        $sliders = $sliderModel->findAll();

        if (empty($sliders)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No sliders found',
                'data' => []
            ]);
        }

        // Add full URLs for each image
        $sliderData = array_map(function($slider) {
            $slider['image_url'] = base_url('uploads/sliders/' . $slider['image']);
            return $slider;
        }, $sliders);

        return $this->response->setJSON([
            'status' => true,
            'count' => count($sliderData),
            'data' => $sliderData
        ]);
    }
}
