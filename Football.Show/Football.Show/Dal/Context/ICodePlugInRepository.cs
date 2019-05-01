using Football.Show.Entities;
using Football.Show.Entities.Enum;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Dal.Context
{
    public interface ICodePlugInRepository
    {
        Task<IEnumerable<CodePlugIn>> GetCodePlugIns(CodeType codeType);
    }
}
