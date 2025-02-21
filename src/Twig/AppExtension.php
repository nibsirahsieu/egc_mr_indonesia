<?php 

namespace App\Twig;

use App\Common\LazifyImageContent;
use App\Common\UploadHelper;
use Oneup\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class AppExtension extends AbstractExtension implements EventSubscriberInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UploaderHelper::class,
            LazifyImageContent::class
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('obfuscate_email', [$this, 'obfuscateEmail']),
            new TwigFilter('lazify_image_content', [$this, 'lazifyImageContent']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'uploadedAsset']),
            new TwigFunction('header_footer_scripts', [AppRuntimeExtension::class, 'getHeaderFooterScript']),
            new TwigFunction('sectors', [AppRuntimeExtension::class, 'getSectors']),
            new TwigFunction('services', [AppRuntimeExtension::class, 'getServices']),
        ];
    }

    public function uploadedAsset(string $assetPath): string
    {
        return $this->container->get(UploadHelper::class)->getPublicUrl($assetPath);
    }

    //https://www.maurits.vdschee.nl/php_hide_email/
    public function obfuscateEmail(string $email): string
    {
        $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
        $key = str_shuffle($character_set); 
        $cipher_text = ''; 
        $id = 'e'.rand(1,999999999);

        for ($i=0; $i<strlen($email); $i+=1) {
            $cipher_text .= $key[strpos($character_set,$email[$i])];
        }

        $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
        $script .= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
        $script .= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
        $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; 
        $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';

        return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
    }

    public function lazifyImageContent(string $content): string
    {
        return $this->container->get(LazifyImageContent::class)->proceed($content);
    }

    public function getName(): string
    {
        return 'app_extension';
    }
}
