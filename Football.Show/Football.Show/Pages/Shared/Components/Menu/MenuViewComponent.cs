using Football.Show.Dal.Context;
using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Pages.Shared.Components.Menu
{
    [ViewComponent(Name = "Menu")]
    public class MenuViewComponent: ViewComponent
    {
        private readonly ICategoryRepository _categoryRepository;
        public MenuViewComponent(ICategoryRepository categoryRepository)
        {
            _categoryRepository = categoryRepository;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var menu = await _categoryRepository.GetMenuCategories();

            return View(menu);
        }
    }
}
