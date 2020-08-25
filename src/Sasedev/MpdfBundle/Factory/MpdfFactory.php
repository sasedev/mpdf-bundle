<?php
namespace Sasedev\MpdfBundle\Factory;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * Sasedev\MpdfBundle\Factory\MpdfFactory
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 1 juin 2020 @ 22:56:28
 */
class MpdfFactory
{

    /**
     *
     * @var string
     */
    private $cacheDir = '/tmp';

    /**
     * MpdfFactory constructor.
     *
     * @param string $cacheDir
     */
    public function __construct(string $cacheDir)
    {

        $this->cacheDir = $cacheDir;

    }

    /**
     *
     * @return array
     */
    public function getDefaultConstructorParams(): array
    {

        return [
            'mode' => '',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => '',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'orientation' => 'P'
        ];

    }

    /**
     *
     * @return array
     */
    public function getDefaultConfigVariables(): array
    {

        $configObject = new ConfigVariables();
        return $configObject->getDefaults();

    }

    /**
     *
     * @return array
     */
    public function getDefaultFontVariables(): array
    {

        $fontObject = new FontVariables();
        return $fontObject->getDefaults();

    }

    /**
     * Get an instance of mPDF class
     *
     * @param array $mpdfArgs
     *            arguments for mPDF constror
     * @return Mpdf
     */
    public function createMpdfObject($mpdfArgs = [])
    {

        $defaultOptions = \array_merge($this->getDefaultConstructorParams(), $this->getDefaultConfigVariables(), $this->getDefaultFontVariables(),
            [
                'mode' => 'utf-8',
                'format' => 'A4',
                'tempDir' => $this->cacheDir
            ]);

        $argOptions = \array_merge($defaultOptions, $mpdfArgs);

        $mPdf = new Mpdf($argOptions);

        return $mPdf;

    }

    /**
     *
     * @param Mpdf $mPdf
     * @param string $filename
     * @param int $status
     * @param array $headers
     *
     * @return Response
     */
    public function createInlineResponse(Mpdf $mPdf, ?string $filename = null, ?int $status = 200, ?array $headers = [])
    {

        $content = $mPdf->Output('', Destination::STRING_RETURN);

        $defaultHeaders = [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
            'Pragma' => 'public',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT'
        ];
        if (false == \is_null($filename))
        {
            $defaultHeaders['Content-disposition'] = sprintf('inline; filename="%s"', $filename);
        }
        else
        {
            $defaultHeaders['Content-disposition'] = sprintf('inline; filename="%s"', 'file.pdf');
        }

        $headers = \array_merge($defaultHeaders, $headers);

        return new Response($content, $status, $headers);

    }

    /**
     *
     * @param Mpdf $mPdf
     * @param string $filename
     * @param int $status
     * @param array $headers
     *
     * @return Response
     */
    public function createDownloadResponse(Mpdf $mPdf, string $filename, ?int $status = 200, ?array $headers = [])
    {

        $content = $mPdf->Output('', Destination::STRING_RETURN);

        $defaultHeaders = [
            'Content-Type' => 'application/pdf',
            'Content-Description' => 'File Transfer',
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
            'Pragma' => 'public',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Content-disposition' => sprintf('attachment; filename="%s"', $filename)
        ];

        $headers = \array_merge($headers, $defaultHeaders);

        $headers = \array_merge($defaultHeaders, $headers);

        return new Response($content, $status, $headers);

    }

}

