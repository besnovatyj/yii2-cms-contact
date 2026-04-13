<?php

declare(strict_types=1);

namespace Besnovatyj\Contact\widgets\compose;

use Besnovatyj\Contact\forms\MessageForm;
use Yii;
use yii\base\Widget;

/**
 * Виджет формы отправки сообщения для использования в темах на Bootstrap 5.
 *
 * Совместим с системой шорткодов (besnovatyj/yii2-cms-shortcode).
 * Форма отправляет данные во фронтенд-контроллер модуля Contact:
 * {@see \Besnovatyj\Contact\controllers\frontend\MessageController::actionIndex()}
 *
 * ## Использование через шорткод
 *
 * Базовый:
 * ```
 * [contactForm][/contactForm]
 * ```
 *
 * С параметрами:
 * ```
 * [contactForm, title="Свяжитесь с нами", subtitle="Ответим в течение 24 часов",
 *   showPhone="1", showSubject="1", submitLabel="Отправить заявку"]
 * [/contactForm]
 * ```
 *
 * Внутренний контент отображается под кнопкой отправки:
 * ```
 * [contactForm, title="Обратная связь"]
 *   Или позвоните нам: <strong>+7 (800) 000-00-00</strong>
 * [/contactForm]
 * ```
 *
 * С капчей (требует установленного besnovatyj/yii2-cms-hcaptcha-widget и компонента 'hcaptcha'):
 * ```
 * [contactForm, title="Свяжитесь с нами", showCaptcha="1"][/contactForm]
 * ```
 *
 * ## Прямое использование в шаблоне темы
 *
 * ```php
 * echo \Besnovatyj\Contact\widgets\compose\ComposeWidget::widget([
 *     'title'       => 'Напишите нам',
 *     'subtitle'    => 'Ответим в течение одного рабочего дня',
 *     'showPhone'   => true,
 *     'showSubject' => true,
 *     'submitLabel' => 'Отправить сообщение',
 * ]);
 * ```
 */
class ComposeWidget extends Widget
{
    // ─── Параметры отображения ────────────────────────────────────────────────

    /**
     * Заголовок формы.
     */
    public string $title = 'Напишите нам';

    /**
     * Подзаголовок / описание под заголовком формы.
     * null — не отображать.
     */
    public ?string $subtitle = null;

    /**
     * Показывать поле «Имя».
     */
    public bool $showName = true;

    /**
     * Показывать поле «Телефон».
     */
    public bool $showPhone = false;

    /**
     * Показывать поле «Тема сообщения».
     */
    public bool $showSubject = false;

    /**
     * Текст кнопки отправки.
     */
    public string $submitLabel = 'Отправить';

    /**
     * CSS-классы корневого блока-обёртки виджета.
     */
    public string $wrapperClass = 'contact-compose-widget';

    // ─── Защита от ботов ─────────────────────────────────────────────────────

    /**
     * Показывать hCaptcha перед кнопкой отправки.
     * Требует установленного пакета besnovatyj/yii2-cms-hcaptcha-widget
     * и зарегистрированного компонента приложения (см. $captchaComponentId).
     *
     * Через шорткод: showCaptcha="1"
     */
    public bool $showCaptcha = false;

    /**
     * ID компонента hCaptcha в контейнере Yii2 (Yii::$app->get($captchaComponentId)).
     * Используется для проверки наличия компонента перед рендером виджета.
     */
    public string $captchaComponentId = 'hcaptcha';

    // ─── Совместимость с шорткодами ───────────────────────────────────────────

    /**
     * Внутренний контент шорткода (поддержка besnovatyj/yii2-cms-shortcode).
     * Отображается под кнопкой отправки как дополнительная информация.
     * Может содержать HTML.
     */
    public ?string $content = null;

    /**
     * Дополнительные HTML-атрибуты для корневого блока-обёртки.
     * Также служит хранилищем для произвольных атрибутов шорткода (через __set()).
     *
     * @var array<string, mixed>
     */
    public array $options = [];

    // ─── Магические методы (поддержка произвольных атрибутов шорткодов) ──────

    /**
     * Перехватывает установку неизвестных свойств и сохраняет их в $options.
     * Позволяет передавать произвольные HTML-атрибуты (data-*, id, class и т.д.)
     * через параметры шорткода.
     *
     * {@inheritdoc}
     */
    public function __set($name, $value): void
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
            return;
        }
        // Произвольные атрибуты из шорткодов (data-*, id, class, ...)
        $this->options[$name] = $value;
    }

    /**
     * Разрешает установку любых свойств (для захвата атрибутов шорткодов в $options).
     *
     * {@inheritdoc}
     */
    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true): bool
    {
        return true;
    }

    // ─── Вспомогательные методы ───────────────────────────────────────────────

    /**
     * Проверяет, нужно ли рендерить hCaptcha.
     * Возвращает true только если:
     * - showCaptcha = true
     * - компонент hCaptcha зарегистрирован в приложении
     *
     * Если компонент не установлен — форма работает без капчи без ошибок.
     */
    public function isCaptchaActive(): bool
    {
        return $this->showCaptcha && Yii::$app->has($this->captchaComponentId);
    }

    // ─── Рендеринг ────────────────────────────────────────────────────────────

    /**
     * Рендерит форму отправки сообщения.
     *
     * {@inheritdoc}
     */
    public function run(): string
    {
        $form = new MessageForm();

        return $this->render('contact-form', [
            'widget' => $this,
            'form'   => $form,
        ]);
    }
}
