<?php

namespace App\Services;

class TestResultMessageService
{
    static  public function getAnxietyResult($score)
    {
        if ($score <= 15) {
            return [
                'result' => 'قلق منخفض جدًا (طبيعي)',
                'message' => 'لديك قلق منخفض جدًا (طبيعي تمامًا)... سيساعدك هذا التطبيق في التعرف على طرق للمحافظة على حالتك الإيجابية.',
                'image' => asset('/storage/tests_results_images/Enthusiastic-cuate.svg')
            ];
        } elseif ($score <= 25) {
            return [
                'result' => 'قلق منخفض',
                'message' => 'لديك قلق منخفض... سيساعدك هذا التطبيق في معرفة ما يمكنك فعله بالضبط لتحافظ على توازنك النفسي.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg')
            ];
        } elseif ($score <= 30) {
            return [
                'result' => 'قلق متوسط',
                'message' => 'لديك قلق متوسط... سيساعدك هذا التطبيق في تنظيم مشاعرك ومعرفة طرق التحكم في قلقك.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg')
            ];
        } elseif ($score <= 35) {
            return [
                'result' => 'قلق فوق المتوسط',
                'message' => 'لديك قلق فوق المتوسط... قد يقترح عليك التطبيق التحدث مع مختص إذا لزم الأمر.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg')
            ];
        } else {
            return [
                'result' => 'قلق مرتفع',
                'message' => 'لديك قلق مرتفع... سيساعدك هذا التطبيق في متابعة حالتك وتوصيلك بالجهات المناسبة.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg')
            ];
        }
    }

    static  public function getDepressionResult($score)
    {
        if ($score <= 9) {
            return [
                'result' => 'لا تعاني من اكتئاب',
                'message' => 'حالتك النفسية مستقرة... سيساعدك هذا التطبيق في تعزيز سعادتك وتحقيق أهدافك النفسية.',
                'image' => asset('/storage/tests_results_images/Enthusiastic-cuate.svg'),
            ];
        } elseif ($score <= 15) {
            return [
                'result' => 'اكتئاب بسيط',
                'message' => 'تخطي هذا الشعور ممكن... سيساعدك هذا التطبيق في مراقبة حالتك واقتراح أنشطة تساعدك على الشعور بتحسن.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg'),
            ];
        } elseif ($score <= 23) {
            return [
                'result' => 'اكتئاب متوسط',
                'message' => 'من المهم الاهتمام بحالتك... سيساعدك التطبيق على فهم حالتك بشكل أعمق وتقديم خطوات واضحة للتعامل معها.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg'),
            ];
        } elseif ($score <= 30) {
            return [
                'result' => 'اكتئاب شديد',
                'message' => 'أنت تمر بمرحلة صعبة... من الضروري استشارة مختص، وسيساعدك التطبيق في إيجاد الدعم المناسب.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg'),
            ];
        } else {
            return [
                'result' => 'اكتئاب شديد جدًا',
                'message' => 'يرجى التحدث مع مختص في أقرب وقت... هذا التطبيق سيكون دليلك للخطوات الأولى.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg'),
            ];
        }
    }

    static  public function getSpenceResult($score)
    {
        if ($score <= 60) {
            return [
                'result' => 'طبيعي',
                'message' => 'لا داعي للقلق، حالتك النفسية طبيعية... سيساعدك التطبيق في تعزيز صحتك النفسية وتطوير مهاراتك.',
                'image' => asset('/storage/tests_results_images/Enthusiastic-cuate.svg')
            ];
        } else {
            return [
                'result' => 'قلق مرتفع',
                'message' => 'من المفيد التحدث مع مختص... سيساعدك هذا التطبيق في التعبير عن مشاعرك والتعامل معها بشكل صحي.',
                'image' => asset('/storage/tests_results_images/Tiredness-bro.svg')
            ];
        }
    }
}
