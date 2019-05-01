using Football.Show.Dal.Context;
using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Pages.Shared.Components.Script
{
    [ViewComponent(Name = "Script")]
    public class ScriptViewComponent: ViewComponent
    {
        private readonly ICodePlugInRepository _codePlugInRepository;
        public ScriptViewComponent(ICodePlugInRepository codePlugInRepository)
        {
            _codePlugInRepository = codePlugInRepository;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var code = await _codePlugInRepository.GetCodePlugIns(Entities.Enum.CodeType.Script);

            return View(code);
        }
    }
}
