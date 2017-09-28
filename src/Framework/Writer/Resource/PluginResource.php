<?php declare(strict_types=1);

namespace Shopware\Framework\Write\Resource;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Write\Field\BoolField;
use Shopware\Framework\Write\Field\DateField;
use Shopware\Framework\Write\Field\IntField;
use Shopware\Framework\Write\Field\LongTextField;
use Shopware\Framework\Write\Field\LongTextWithHtmlField;
use Shopware\Framework\Write\Field\StringField;
use Shopware\Framework\Write\Field\SubresourceField;
use Shopware\Framework\Write\Field\UuidField;
use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\Resource;

class PluginResource extends Resource
{
    protected const UUID_FIELD = 'uuid';
    protected const NAME_FIELD = 'name';
    protected const LABEL_FIELD = 'label';
    protected const DESCRIPTION_FIELD = 'description';
    protected const DESCRIPTION_LONG_FIELD = 'descriptionLong';
    protected const ACTIVE_FIELD = 'active';
    protected const INSTALLATION_DATE_FIELD = 'installationDate';
    protected const UPDATE_DATE_FIELD = 'updateDate';
    protected const REFRESH_DATE_FIELD = 'refreshDate';
    protected const AUTHOR_FIELD = 'author';
    protected const COPYRIGHT_FIELD = 'copyright';
    protected const LICENSE_FIELD = 'license';
    protected const VERSION_FIELD = 'version';
    protected const SUPPORT_FIELD = 'support';
    protected const CHANGES_FIELD = 'changes';
    protected const LINK_FIELD = 'link';
    protected const STORE_VERSION_FIELD = 'storeVersion';
    protected const STORE_DATE_FIELD = 'storeDate';
    protected const CAPABILITY_UPDATE_FIELD = 'capabilityUpdate';
    protected const CAPABILITY_INSTALL_FIELD = 'capabilityInstall';
    protected const CAPABILITY_ENABLE_FIELD = 'capabilityEnable';
    protected const UPDATE_SOURCE_FIELD = 'updateSource';
    protected const UPDATE_VERSION_FIELD = 'updateVersion';
    protected const CAPABILITY_SECURE_UNINSTALL_FIELD = 'capabilitySecureUninstall';

    public function __construct()
    {
        parent::__construct('plugin');

        $this->primaryKeyFields[self::UUID_FIELD] = (new UuidField('uuid'))->setFlags(new Required());
        $this->fields[self::NAME_FIELD] = (new StringField('name'))->setFlags(new Required());
        $this->fields[self::LABEL_FIELD] = (new StringField('label'))->setFlags(new Required());
        $this->fields[self::DESCRIPTION_FIELD] = new LongTextField('description');
        $this->fields[self::DESCRIPTION_LONG_FIELD] = new LongTextWithHtmlField('description_long');
        $this->fields[self::ACTIVE_FIELD] = (new BoolField('active'))->setFlags(new Required());
        $this->fields[self::INSTALLATION_DATE_FIELD] = new DateField('installation_date');
        $this->fields[self::UPDATE_DATE_FIELD] = new DateField('update_date');
        $this->fields[self::REFRESH_DATE_FIELD] = new DateField('refresh_date');
        $this->fields[self::AUTHOR_FIELD] = new StringField('author');
        $this->fields[self::COPYRIGHT_FIELD] = new StringField('copyright');
        $this->fields[self::LICENSE_FIELD] = new StringField('license');
        $this->fields[self::VERSION_FIELD] = (new StringField('version'))->setFlags(new Required());
        $this->fields[self::SUPPORT_FIELD] = new StringField('support');
        $this->fields[self::CHANGES_FIELD] = new LongTextField('changes');
        $this->fields[self::LINK_FIELD] = new StringField('link');
        $this->fields[self::STORE_VERSION_FIELD] = new StringField('store_version');
        $this->fields[self::STORE_DATE_FIELD] = new DateField('store_date');
        $this->fields[self::CAPABILITY_UPDATE_FIELD] = (new IntField('capability_update'))->setFlags(new Required());
        $this->fields[self::CAPABILITY_INSTALL_FIELD] = (new IntField('capability_install'))->setFlags(new Required());
        $this->fields[self::CAPABILITY_ENABLE_FIELD] = (new IntField('capability_enable'))->setFlags(new Required());
        $this->fields[self::UPDATE_SOURCE_FIELD] = new StringField('update_source');
        $this->fields[self::UPDATE_VERSION_FIELD] = new StringField('update_version');
        $this->fields[self::CAPABILITY_SECURE_UNINSTALL_FIELD] = new IntField('capability_secure_uninstall');
        $this->fields['configForms'] = new SubresourceField(\Shopware\Framework\Write\Resource\ConfigFormResource::class);
        $this->fields['paymentMethods'] = new SubresourceField(\Shopware\PaymentMethod\Writer\Resource\PaymentMethodResource::class);
        $this->fields['shopTemplates'] = new SubresourceField(\Shopware\ShopTemplate\Writer\Resource\ShopTemplateResource::class);
        $this->fields['shoppingWorldComponents'] = new SubresourceField(\Shopware\Framework\Write\Resource\ShoppingWorldComponentResource::class);
    }

    public function getWriteOrder(): array
    {
        return [
            \Shopware\Framework\Write\Resource\ConfigFormResource::class,
            \Shopware\PaymentMethod\Writer\Resource\PaymentMethodResource::class,
            \Shopware\Framework\Write\Resource\PluginResource::class,
            \Shopware\ShopTemplate\Writer\Resource\ShopTemplateResource::class,
            \Shopware\Framework\Write\Resource\ShoppingWorldComponentResource::class,
        ];
    }

    public static function createWrittenEvent(array $updates, TranslationContext $context, array $errors = []): \Shopware\Framework\Event\PluginWrittenEvent
    {
        $event = new \Shopware\Framework\Event\PluginWrittenEvent($updates[self::class] ?? [], $context, $errors);

        unset($updates[self::class]);

        if (!empty($updates[\Shopware\Framework\Write\Resource\ConfigFormResource::class])) {
            $event->addEvent(\Shopware\Framework\Write\Resource\ConfigFormResource::createWrittenEvent($updates, $context));
        }

        if (!empty($updates[\Shopware\PaymentMethod\Writer\Resource\PaymentMethodResource::class])) {
            $event->addEvent(\Shopware\PaymentMethod\Writer\Resource\PaymentMethodResource::createWrittenEvent($updates, $context));
        }

        if (!empty($updates[\Shopware\Framework\Write\Resource\PluginResource::class])) {
            $event->addEvent(\Shopware\Framework\Write\Resource\PluginResource::createWrittenEvent($updates, $context));
        }

        if (!empty($updates[\Shopware\ShopTemplate\Writer\Resource\ShopTemplateResource::class])) {
            $event->addEvent(\Shopware\ShopTemplate\Writer\Resource\ShopTemplateResource::createWrittenEvent($updates, $context));
        }

        if (!empty($updates[\Shopware\Framework\Write\Resource\ShoppingWorldComponentResource::class])) {
            $event->addEvent(\Shopware\Framework\Write\Resource\ShoppingWorldComponentResource::createWrittenEvent($updates, $context));
        }

        return $event;
    }
}