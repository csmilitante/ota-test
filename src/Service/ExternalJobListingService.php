<?php

namespace App\Service;

class ExternalJobListingService
{
    public function getDataFromExternal(): array
    {
        $parsedData = [];
        $xmlFile = simplexml_load_file("https://mrge-group-gmbh.jobs.personio.de/xml", "SimpleXMLElement", LIBXML_NOCDATA);

        foreach ($xmlFile as $externalJobListing) {
            $parsedData[(string) $externalJobListing->id] = [
                'id' => $externalJobListing->id,
                'name' => $externalJobListing->name,
            ];

            foreach ($externalJobListing->jobDescriptions as $jobDescription) {
                foreach ($jobDescription->jobDescription as $jobDescriptionItem) {
                    $parsedData[(string) $externalJobListing->id]['description'][] = [
                        'header' => (string) $jobDescriptionItem->name,
                        'text' => (string) $jobDescriptionItem->value,
                    ];
                }
            }
        }

        return $parsedData;
    }
}
