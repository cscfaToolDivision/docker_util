<?php
namespace CSDT\DockerUtilBundle\Images;

use Docker\API\Model\ImageItem;
use CSDT\CollectionsBundle\Collections\ValueCollection;

/**
 * Image item parser
 *
 * This class is used to cast an image item to Image instance
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class ImageItemParser
{

    /**
     * Get image count
     *
     * Return the image count of an image item
     *
     * @param ImageItem $imageItem The imageItem
     *
     * @return number
     */
    public function getImageCount(ImageItem $imageItem)
    {
        return count($imageItem->getRepoTags());
    }

    /**
     * Parse
     *
     * Parse an ImageItem to an Image
     *
     * @param ImageItem $imageItem The imageItem instance to convert
     * @param integer $index The imageItem index to convert
     *
     * @return Image
     */
    public function parse(ImageItem $imageItem, $index)
    {
        list($name, $tag) = explode(":", $imageItem->getRepoTags()[$index]);
        $id = $imageItem->getId();

        $created = new \DateTime();
        $created->setTimestamp($imageItem->getCreated());

        $size = $imageItem->getSize();
        $virtualSize = $imageItem->getVirtualSize();

        $labels = new ValueCollection();
        foreach ($imageItem->getLabels() as $labelKey => $labelValue) {
            $labels->add(
                new Label($labelKey, $labelValue)
            );
        }

        $parentId = $imageItem->getParentId();

        return new Image($name, $tag, $id, $created, $size, $virtualSize, $labels, $parentId);
    }

}
